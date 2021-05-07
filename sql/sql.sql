SELECT * FROM glpi_computers, glpi_computers_items, glpi_monitors WHERE glpi_computers.id = glpi_computers_items.computers_id AND glpi_computers_items.items_id = glpi_monitors.id;
