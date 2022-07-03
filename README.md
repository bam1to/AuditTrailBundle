Audit-Trail bundle
===
Zadanie testowe dla firmy "BoostHigh"

Instalacja
===

1. Utworzyć w folderze z projektem nowy folder pod bundle (np.: /lib, /bundles)
2. Pobrać projekt do tego folderu
3. Wykonać `composer require bam1to/audit-trail`
4. Dodać do nową kofigurację `/config/packages/audit_trail.yaml`
5. Wstawić podstawową konfigurację
```yaml 
audit_trail: 
    tables:
        # - table:
        #       name: 
        #       actions: "save,update,delete"
```
Tabel może być ilekolwiek, każda następna tabela to osobny block kofiguracji `table`

`name` przyjmuje nazwę tabeli w bazie danych

Dostępne są trzy akcje:
- save - działa podczas zapisywania nowego rekordu do tabeli
- update - działa podczas zmiany rekordu tabeli
- delete - działa podczas usuwania rekordu z tabeli

Dane odkładają się do `/var/log/dev.log`