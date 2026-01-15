---
description: Interact with the deepwiki MCP server for knowledge base resources.
---

# DeepWiki MCP Server

Access documentation and knowledge base resources.

## Available Tools

### `list_resources`

List all available resources from the deepwiki server.

```xml
<list_resources>
{"ServerName": "deepwiki"}
</list_resources>
```

---

### `read_resource`

Read the content of a specific resource.

**Parameters:**
- `ServerName` (required): "deepwiki"
- `Uri` (required): The resource URI obtained from list_resources

```xml
<read_resource>
{"ServerName": "deepwiki", "Uri": "deepwiki://resource-uri-here"}
</read_resource>
```

## Usage Pattern

1. First, list available resources to discover what's available
2. Then, read specific resources by their URI

```xml
<!-- Step 1: Discover resources -->
<list_resources>
{"ServerName": "deepwiki"}
</list_resources>

<!-- Step 2: Read a specific resource -->
<read_resource>
{"ServerName": "deepwiki", "Uri": "deepwiki://docs/getting-started"}
</read_resource>
```
