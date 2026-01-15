---
description: Interact with the visual-browser MCP server for visual browser automation.
---

# Visual Browser MCP Server

Visual browser automation with screenshot-based interaction.

> **Note:** Prefer Playwright MCP (`mcp4_`) for heavy AJAX pages. Visual Browser has a 30-second WebSocket timeout.

## Available Tools

### `mcp7_browser_navigate`

Navigate to a URL.

**Parameters:**
- `url` (required): URL to navigate to

```xml
<mcp7_browser_navigate>
{"url": "https://www.google.com"}
</mcp7_browser_navigate>
```

---

### `mcp7_browser_screenshot`

Take a screenshot of the current page.

```xml
<mcp7_browser_screenshot />
```

---

### `mcp7_browser_snapshot`

Capture accessibility snapshot (get element references).

```xml
<mcp7_browser_snapshot />
```

---

### `mcp7_browser_click`

Click on an element.

**Parameters:**
- `element` (required): Human-readable element description
- `ref` (required): Element reference from snapshot

```xml
<mcp7_browser_click>
{"element": "Submit button", "ref": "button.submit"}
</mcp7_browser_click>
```

---

### `mcp7_browser_type`

Type text into an element.

**Parameters:**
- `element` (required): Human-readable element description
- `ref` (required): Element reference from snapshot
- `text` (required): Text to type
- `submit` (required): Whether to press Enter after

```xml
<mcp7_browser_type>
{"element": "Search box", "ref": "input#search", "text": "hello", "submit": false}
</mcp7_browser_type>

<!-- Type and submit -->
<mcp7_browser_type>
{"element": "Search box", "ref": "input#search", "text": "hello", "submit": true}
</mcp7_browser_type>
```

---

### `mcp7_browser_hover`

Hover over an element.

**Parameters:**
- `element` (required): Human-readable element description
- `ref` (required): Element reference from snapshot

```xml
<mcp7_browser_hover>
{"element": "Dropdown menu", "ref": "div.dropdown"}
</mcp7_browser_hover>
```

---

### `mcp7_browser_select_option`

Select an option in a dropdown.

**Parameters:**
- `element` (required): Human-readable element description
- `ref` (required): Element reference from snapshot
- `values` (required): Array of values to select

```xml
<mcp7_browser_select_option>
{"element": "Country selector", "ref": "select#country", "values": ["US"]}
</mcp7_browser_select_option>
```

---

### `mcp7_browser_press_key`

Press a keyboard key.

**Parameters:**
- `key` (required): Key name (e.g., "Enter", "Escape", "ArrowDown")

```xml
<mcp7_browser_press_key>
{"key": "Enter"}
</mcp7_browser_press_key>

<mcp7_browser_press_key>
{"key": "Escape"}
</mcp7_browser_press_key>
```

---

### `mcp7_browser_wait`

Wait for a specified time.

**Parameters:**
- `time` (required): Seconds to wait

```xml
<mcp7_browser_wait>
{"time": 2}
</mcp7_browser_wait>
```

---

### `mcp7_browser_go_back`

Navigate back in browser history.

```xml
<mcp7_browser_go_back />
```

---

### `mcp7_browser_go_forward`

Navigate forward in browser history.

```xml
<mcp7_browser_go_forward />
```

---

### `mcp7_browser_get_console_logs`

Get browser console logs.

```xml
<mcp7_browser_get_console_logs />
```

## Typical Workflow

```xml
<!-- 1. Navigate -->
<mcp7_browser_navigate>
{"url": "https://example.com"}
</mcp7_browser_navigate>

<!-- 2. Take snapshot to get refs -->
<mcp7_browser_snapshot />

<!-- 3. Click using ref from snapshot -->
<mcp7_browser_click>
{"element": "Login button", "ref": "button#login"}
</mcp7_browser_click>

<!-- 4. Type credentials -->
<mcp7_browser_type>
{"element": "Username", "ref": "input#user", "text": "admin", "submit": false}
</mcp7_browser_type>

<!-- 5. Wait for page load -->
<mcp7_browser_wait>
{"time": 2}
</mcp7_browser_wait>

<!-- 6. Take screenshot to verify -->
<mcp7_browser_screenshot />
```

## Limitations vs Playwright MCP

| Feature | Visual Browser (mcp7) | Playwright (mcp4) |
|---------|----------------------|-------------------|
| Long waits (>30s) | ❌ Timeout | ✅ Works |
| Console logs | ⚠️ Limited | ✅ Full |
| Network requests | ❌ Not available | ✅ Available |
| Custom JS | ❌ Not available | ✅ browser_run_code |
| AJAX-heavy pages | ⚠️ May timeout | ✅ Reliable |
