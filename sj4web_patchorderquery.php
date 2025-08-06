<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Sj4web_PatchOrderQuery extends Module
{
    public function __construct()
    {
        $this->name = 'sj4web_patchorderquery';
        $this->version = '1.0.0';
        $this->author = 'SJ4WEB.FR';
        $this->tab = 'administration';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('SJ4WEB - Patch requête commandes', [], 'Modules.Sj4webpatchorderquery.Admin');
        $this->description = $this->trans('Optimisation du champ "nouveau client" dans la liste des commandes.', [], 'Modules.Sj4webpatchorderquery.Admin');
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => _PS_VERSION_];
    }

    public function install()
    {
        return parent::install() && $this->registerHook('actionOrderGridQueryBuilderModifier');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    /**
     * Hook pour modifier le QueryBuilder de la grille des commandes
     */
    public function hookActionOrderGridQueryBuilderModifier(array $params)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $qb */
        $qb = $params['search_query_builder'];

        try {
            // 🔎 On regarde tous les SELECTs
            $selects = $qb->getQueryPart('select');
            $modified = false;

            $qb->resetQueryPart('select');

            foreach ($selects as $select) {
                // 🎯 Pattern plus robuste pour détecter la sous-requête "new customer"
                if (preg_match('/SELECT IF\(count\(so\.id_order\).*AS new$/i', $select)) {
                    // Version optimisée de la sous-requête
                    $optimizedSelect = sprintf(
                        'IF((SELECT so.id_order FROM %sorders so WHERE so.id_customer = o.id_customer AND so.id_order < o.id_order LIMIT 1) IS NOT NULL, 0, 1) AS new',
                        _DB_PREFIX_
                    );

                    $qb->addSelect($optimizedSelect);
                    $modified = true;

                    // Log pour debug (optionnel)
                    if (defined('_PS_MODE_DEV_') && _PS_MODE_DEV_) {
                        PrestaShopLogger::addLog('SJ4WEB: Requête newCustomer optimisée', 1);
                    }
                } else {
                    $qb->addSelect($select);
                }
            }

        } catch (Exception $e) {
            // En cas d'erreur, on log et on laisse la requête originale
            PrestaShopLogger::addLog(
                'SJ4WEB PatchOrderQuery Error: ' . $e->getMessage(),
                3,
                null,
                'Module'
            );
        }
    }

    /**
     * Méthode utilitaire pour vérifier si le patch est actif
     */
    public function isActive()
    {
        return $this->active;
    }
}