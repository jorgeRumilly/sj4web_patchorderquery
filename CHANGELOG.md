# Journal des modifications – SJ4WEB PatchOrderQuery

## [1.0.0] – 2025-08-06
### Ajout
- Première version du module.
- Hook `actionOrderGridQueryBuilderModifier` intercepté.
- Remplacement de la sous-requête `COUNT()` par une version optimisée avec `LIMIT 1`.
- Log ajouté si `_PS_MODE_DEV_` est activé.
