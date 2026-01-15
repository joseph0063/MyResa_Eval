---
description: Interact with the filesystem MCP server for file operations.
---

# Filesystem MCP Server

Perform file and directory operations within allowed directories.

## Available Tools

### `mcp1_read_text_file`

Read the contents of a text file.

**Parameters:**
- `path` (required): Absolute path to the file
- `head` (optional): Return only the first N lines
- `tail` (optional): Return only the last N lines

```xml
<mcp1_read_text_file>
{"path": "/path/to/file.txt"}
</mcp1_read_text_file>

<!-- Read first 10 lines -->
<mcp1_read_text_file>
{"path": "/path/to/file.txt", "head": 10}
</mcp1_read_text_file>

<!-- Read last 20 lines -->
<mcp1_read_text_file>
{"path": "/path/to/file.txt", "tail": 20}
</mcp1_read_text_file>
```

---

### `mcp1_read_multiple_files`

Read multiple files simultaneously.

**Parameters:**
- `paths` (required): Array of file paths

```xml
<mcp1_read_multiple_files>
{"paths": ["/path/to/file1.txt", "/path/to/file2.txt", "/path/to/file3.txt"]}
</mcp1_read_multiple_files>
```

---

### `mcp1_write_file`

Create or overwrite a file with new content.

**Parameters:**
- `path` (required): Absolute path to the file
- `content` (required): Content to write

```xml
<mcp1_write_file>
{"path": "/path/to/new-file.txt", "content": "Hello, World!"}
</mcp1_write_file>
```

---

### `mcp1_edit_file`

Make line-based edits to a file (find and replace).

**Parameters:**
- `path` (required): Absolute path to the file
- `edits` (required): Array of {oldText, newText} objects
- `dryRun` (optional): Preview changes without applying

```xml
<mcp1_edit_file>
{"path": "/path/to/file.txt", "edits": [{"oldText": "old text", "newText": "new text"}]}
</mcp1_edit_file>

<!-- Dry run to preview -->
<mcp1_edit_file>
{"path": "/path/to/file.txt", "edits": [{"oldText": "foo", "newText": "bar"}], "dryRun": true}
</mcp1_edit_file>
```

---

### `mcp1_list_directory`

List files and directories in a path.

**Parameters:**
- `path` (required): Absolute path to the directory

```xml
<mcp1_list_directory>
{"path": "/path/to/directory"}
</mcp1_list_directory>
```

---

### `mcp1_list_directory_with_sizes`

List files and directories with their sizes.

**Parameters:**
- `path` (required): Absolute path to the directory
- `sortBy` (optional): "name" or "size"

```xml
<mcp1_list_directory_with_sizes>
{"path": "/path/to/directory", "sortBy": "size"}
</mcp1_list_directory_with_sizes>
```

---

### `mcp1_directory_tree`

Get a recursive tree view of files and directories as JSON.

**Parameters:**
- `path` (required): Absolute path to the directory
- `excludePatterns` (optional): Glob patterns to exclude

```xml
<mcp1_directory_tree>
{"path": "/path/to/directory"}
</mcp1_directory_tree>

<!-- With exclusions -->
<mcp1_directory_tree>
{"path": "/path/to/directory", "excludePatterns": ["node_modules", "*.log"]}
</mcp1_directory_tree>
```

---

### `mcp1_create_directory`

Create a new directory (including nested directories).

**Parameters:**
- `path` (required): Absolute path for the new directory

```xml
<mcp1_create_directory>
{"path": "/path/to/new/directory"}
</mcp1_create_directory>
```

---

### `mcp1_move_file`

Move or rename files and directories.

**Parameters:**
- `source` (required): Source path
- `destination` (required): Destination path

```xml
<mcp1_move_file>
{"source": "/path/to/old-name.txt", "destination": "/path/to/new-name.txt"}
</mcp1_move_file>
```

---

### `mcp1_search_files`

Search for files matching a glob pattern.

**Parameters:**
- `path` (required): Directory to search in
- `pattern` (required): Glob pattern (e.g., "*.js", "**/*.php")
- `excludePatterns` (optional): Patterns to exclude

```xml
<mcp1_search_files>
{"path": "/path/to/directory", "pattern": "**/*.php"}
</mcp1_search_files>

<!-- With exclusions -->
<mcp1_search_files>
{"path": "/path/to/directory", "pattern": "**/*.js", "excludePatterns": ["node_modules/**"]}
</mcp1_search_files>
```

---

### `mcp1_get_file_info`

Get detailed metadata about a file or directory.

**Parameters:**
- `path` (required): Absolute path

```xml
<mcp1_get_file_info>
{"path": "/path/to/file.txt"}
</mcp1_get_file_info>
```

---

### `mcp1_read_media_file`

Read an image or audio file as base64.

**Parameters:**
- `path` (required): Absolute path to the media file

```xml
<mcp1_read_media_file>
{"path": "/path/to/image.png"}
</mcp1_read_media_file>
```

---

### `mcp1_list_allowed_directories`

List directories that the MCP server is allowed to access.

```xml
<mcp1_list_allowed_directories />
```

## Notes

- All paths must be absolute
- Operations are restricted to allowed directories
- Use `dryRun` on `edit_file` to preview changes before applying
