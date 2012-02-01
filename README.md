To use:

In deps:

[TableauTrustedServerBundle]
    git=http://github.com/RogerWebb/TableauTrustedServerBundle.git
    target=/bundles/Tableau/Bundle/TableauTrustedServerBundle
    version=v0.1

In config.yml

```yml

tableau_trusted_server:
    host:  "tableau.myserver.com"
    port:  8000
```

And in your controller:

```php

$tabsvc = $this->get('tableau.trusted_server.authentication');

$unique_id = $tabsvc->getUniqueIdFor("TableauUser");

$url = $tabsvc->constructViewUrl("MyWorkbook", "MyView", $unique_id);
```
