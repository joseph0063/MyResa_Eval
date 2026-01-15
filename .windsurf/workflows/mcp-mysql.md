---
description: Interact with the mysql MCP server to execute SQL queries.
---

# MySQL MCP Server

Execute SQL queries against a MySQL database.

## Available Tools

### `mcp3_mysql_query`

Run SQL queries (SELECT, INSERT, UPDATE, DELETE, DDL).

**Parameters:**
- `sql` (required): The SQL query to execute

**Examples:**

```xml
<!-- SELECT query -->
<mcp3_mysql_query>
{"sql": "SELECT * FROM wp_posts LIMIT 10;"}
</mcp3_mysql_query>

<!-- SELECT with WHERE -->
<mcp3_mysql_query>
{"sql": "SELECT * FROM wp_users WHERE user_email LIKE '%@gmail.com';"}
</mcp3_mysql_query>

<!-- INSERT -->
<mcp3_mysql_query>
{"sql": "INSERT INTO wp_options (option_name, option_value) VALUES ('my_option', 'my_value');"}
</mcp3_mysql_query>

<!-- UPDATE -->
<mcp3_mysql_query>
{"sql": "UPDATE wp_options SET option_value = 'new_value' WHERE option_name = 'my_option';"}
</mcp3_mysql_query>

<!-- DELETE -->
<mcp3_mysql_query>
{"sql": "DELETE FROM wp_options WHERE option_name = 'my_option';"}
</mcp3_mysql_query>

<!-- SHOW TABLES -->
<mcp3_mysql_query>
{"sql": "SHOW TABLES;"}
</mcp3_mysql_query>

<!-- DESCRIBE table -->
<mcp3_mysql_query>
{"sql": "DESCRIBE wp_posts;"}
</mcp3_mysql_query>

<!-- JOIN query -->
<mcp3_mysql_query>
{"sql": "SELECT p.post_title, u.display_name FROM wp_posts p JOIN wp_users u ON p.post_author = u.ID LIMIT 10;"}
</mcp3_mysql_query>
```

## Notes

- Always use parameterized queries when possible to prevent SQL injection
- Be careful with DELETE and UPDATE queries - always use WHERE clauses
- For large datasets, use LIMIT to avoid overwhelming output
