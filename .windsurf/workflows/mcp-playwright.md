---
description: Interact with the playwright MCP server for browser automation.
---

# Playwright MCP Server

Browser automation and testing with Playwright.

## Available Tools

### `mcp4_browser_navigate`

Navigate to a URL.

**Parameters:**
- `url` (required): URL to navigate to

```xml
<mcp4_browser_navigate>
{"url": "https://www.google.com"}
</mcp4_browser_navigate>
```

---

### `mcp4_browser_snapshot`

Capture accessibility snapshot of the current page (better than screenshot for actions).

**Parameters:**
- `filename` (optional): Save to markdown file

```xml
<mcp4_browser_snapshot />

<!-- Save to file -->
<mcp4_browser_snapshot>
{"filename": "snapshot.md"}
</mcp4_browser_snapshot>
```

---

### `mcp4_browser_click`

Click on an element.

**Parameters:**
- `element` (required): Human-readable element description
- `ref` (required): Element reference from snapshot
- `button` (optional): "left", "right", "middle"
- `doubleClick` (optional): Perform double click
- `modifiers` (optional): ["Alt", "Control", "Shift", "Meta"]

```xml
<mcp4_browser_click>
{"element": "Submit button", "ref": "button[name='submit']"}
</mcp4_browser_click>

<!-- Right click -->
<mcp4_browser_click>
{"element": "Context menu target", "ref": "div.target", "button": "right"}
</mcp4_browser_click>

<!-- Double click -->
<mcp4_browser_click>
{"element": "Editable cell", "ref": "td.editable", "doubleClick": true}
</mcp4_browser_click>
```

---

### `mcp4_browser_type`

Type text into an editable element.

**Parameters:**
- `element` (required): Human-readable element description
- `ref` (required): Element reference from snapshot
- `text` (required): Text to type
- `submit` (optional): Press Enter after typing
- `slowly` (optional): Type one character at a time

```xml
<mcp4_browser_type>
{"element": "Search input", "ref": "input[name='q']", "text": "hello world"}
</mcp4_browser_type>

<!-- Type and submit -->
<mcp4_browser_type>
{"element": "Search input", "ref": "input[name='q']", "text": "hello world", "submit": true}
</mcp4_browser_type>
```

---

### `mcp4_browser_fill_form`

Fill multiple form fields at once.

**Parameters:**
- `fields` (required): Array of field objects with name, ref, type, value

```xml
<mcp4_browser_fill_form>
{
  "fields": [
    {"name": "Username", "ref": "input#username", "type": "textbox", "value": "john"},
    {"name": "Password", "ref": "input#password", "type": "textbox", "value": "secret"},
    {"name": "Remember me", "ref": "input#remember", "type": "checkbox", "value": "true"}
  ]
}
</mcp4_browser_fill_form>
```

---

### `mcp4_browser_select_option`

Select an option in a dropdown.

**Parameters:**
- `element` (required): Human-readable element description
- `ref` (required): Element reference from snapshot
- `values` (required): Array of values to select

```xml
<mcp4_browser_select_option>
{"element": "Country dropdown", "ref": "select#country", "values": ["US"]}
</mcp4_browser_select_option>

<!-- Multi-select -->
<mcp4_browser_select_option>
{"element": "Tags select", "ref": "select#tags", "values": ["tag1", "tag2"]}
</mcp4_browser_select_option>
```

---

### `mcp4_browser_hover`

Hover over an element.

**Parameters:**
- `element` (required): Human-readable element description
- `ref` (required): Element reference from snapshot

```xml
<mcp4_browser_hover>
{"element": "Menu item", "ref": "li.menu-item"}
</mcp4_browser_hover>
```

---

### `mcp4_browser_drag`

Drag and drop between two elements.

**Parameters:**
- `startElement` (required): Source element description
- `startRef` (required): Source element reference
- `endElement` (required): Target element description
- `endRef` (required): Target element reference

```xml
<mcp4_browser_drag>
{
  "startElement": "Draggable item",
  "startRef": "div.draggable",
  "endElement": "Drop zone",
  "endRef": "div.dropzone"
}
</mcp4_browser_drag>
```

---

### `mcp4_browser_press_key`

Press a keyboard key.

**Parameters:**
- `key` (required): Key name (e.g., "Enter", "ArrowDown", "a")

```xml
<mcp4_browser_press_key>
{"key": "Enter"}
</mcp4_browser_press_key>

<mcp4_browser_press_key>
{"key": "Escape"}
</mcp4_browser_press_key>

<mcp4_browser_press_key>
{"key": "ArrowDown"}
</mcp4_browser_press_key>
```

---

### `mcp4_browser_take_screenshot`

Take a screenshot of the page.

**Parameters:**
- `filename` (optional): Save location
- `fullPage` (optional): Capture full scrollable page
- `element` (optional): Element description for element screenshot
- `ref` (optional): Element reference for element screenshot
- `type` (optional): "png" or "jpeg"

```xml
<!-- Viewport screenshot -->
<mcp4_browser_take_screenshot />

<!-- Full page -->
<mcp4_browser_take_screenshot>
{"fullPage": true, "filename": "full-page.png"}
</mcp4_browser_take_screenshot>

<!-- Element screenshot -->
<mcp4_browser_take_screenshot>
{"element": "Header section", "ref": "header.main", "filename": "header.png"}
</mcp4_browser_take_screenshot>
```

---

### `mcp4_browser_wait_for`

Wait for text, text disappearance, or time.

**Parameters:**
- `text` (optional): Text to wait for
- `textGone` (optional): Text to wait to disappear
- `time` (optional): Seconds to wait

```xml
<!-- Wait for text -->
<mcp4_browser_wait_for>
{"text": "Loading complete"}
</mcp4_browser_wait_for>

<!-- Wait for loading to disappear -->
<mcp4_browser_wait_for>
{"textGone": "Loading..."}
</mcp4_browser_wait_for>

<!-- Wait 2 seconds -->
<mcp4_browser_wait_for>
{"time": 2}
</mcp4_browser_wait_for>
```

---

### `mcp4_browser_navigate_back`

Go back to the previous page.

```xml
<mcp4_browser_navigate_back />
```

---

### `mcp4_browser_file_upload`

Upload files.

**Parameters:**
- `paths` (optional): Array of absolute file paths (omit to cancel)

```xml
<mcp4_browser_file_upload>
{"paths": ["/path/to/file1.pdf", "/path/to/file2.jpg"]}
</mcp4_browser_file_upload>
```

---

### `mcp4_browser_handle_dialog`

Handle browser dialogs (alert, confirm, prompt).

**Parameters:**
- `accept` (required): Whether to accept the dialog
- `promptText` (optional): Text for prompt dialogs

```xml
<!-- Accept alert -->
<mcp4_browser_handle_dialog>
{"accept": true}
</mcp4_browser_handle_dialog>

<!-- Dismiss confirm -->
<mcp4_browser_handle_dialog>
{"accept": false}
</mcp4_browser_handle_dialog>

<!-- Answer prompt -->
<mcp4_browser_handle_dialog>
{"accept": true, "promptText": "My answer"}
</mcp4_browser_handle_dialog>
```

---

### `mcp4_browser_console_messages`

Get console messages.

**Parameters:**
- `level` (optional): "error", "warning", "info", "debug"

```xml
<mcp4_browser_console_messages>
{"level": "error"}
</mcp4_browser_console_messages>
```

---

### `mcp4_browser_network_requests`

Get network requests since page load.

**Parameters:**
- `includeStatic` (optional): Include static resources

```xml
<mcp4_browser_network_requests />

<!-- Include images, scripts, etc. -->
<mcp4_browser_network_requests>
{"includeStatic": true}
</mcp4_browser_network_requests>
```

---

### `mcp4_browser_tabs`

Manage browser tabs.

**Parameters:**
- `action` (required): "list", "new", "close", "select"
- `index` (optional): Tab index for close/select

```xml
<!-- List tabs -->
<mcp4_browser_tabs>
{"action": "list"}
</mcp4_browser_tabs>

<!-- New tab -->
<mcp4_browser_tabs>
{"action": "new"}
</mcp4_browser_tabs>

<!-- Select tab -->
<mcp4_browser_tabs>
{"action": "select", "index": 0}
</mcp4_browser_tabs>

<!-- Close current tab -->
<mcp4_browser_tabs>
{"action": "close"}
</mcp4_browser_tabs>
```

---

### `mcp4_browser_resize`

Resize browser window.

**Parameters:**
- `width` (required): Width in pixels
- `height` (required): Height in pixels

```xml
<mcp4_browser_resize>
{"width": 1920, "height": 1080}
</mcp4_browser_resize>

<!-- Mobile viewport -->
<mcp4_browser_resize>
{"width": 375, "height": 812}
</mcp4_browser_resize>
```

---

### `mcp4_browser_evaluate`

Execute JavaScript on the page.

**Parameters:**
- `function` (required): JavaScript function string
- `element` (optional): Element description
- `ref` (optional): Element reference

```xml
<!-- Get page title -->
<mcp4_browser_evaluate>
{"function": "() => document.title"}
</mcp4_browser_evaluate>

<!-- Get localStorage -->
<mcp4_browser_evaluate>
{"function": "() => JSON.stringify(localStorage)"}
</mcp4_browser_evaluate>

<!-- Execute on element -->
<mcp4_browser_evaluate>
{"function": "(el) => el.innerText", "element": "Header", "ref": "h1"}
</mcp4_browser_evaluate>
```

---

### `mcp4_browser_run_code`

Run Playwright code snippet.

**Parameters:**
- `code` (required): Async function with page parameter

```xml
<!-- Complex interaction -->
<mcp4_browser_run_code>
{
  "code": "async (page) => { await page.getByRole('button', { name: 'Submit' }).click(); return await page.title(); }"
}
</mcp4_browser_run_code>

<!-- Intercept network requests -->
<mcp4_browser_run_code>
{
  "code": "async (page) => { const requests = []; page.on('request', r => requests.push(r.url())); await page.waitForTimeout(2000); return requests; }"
}
</mcp4_browser_run_code>
```

---

### `mcp4_browser_close`

Close the browser.

```xml
<mcp4_browser_close />
```

---

### `mcp4_browser_install`

Install the browser (if not installed).

```xml
<mcp4_browser_install />
```

## Typical Workflow

```xml
<!-- 1. Navigate -->
<mcp4_browser_navigate>
{"url": "https://example.com/login"}
</mcp4_browser_navigate>

<!-- 2. Take snapshot to get element refs -->
<mcp4_browser_snapshot />

<!-- 3. Fill form using refs from snapshot -->
<mcp4_browser_type>
{"element": "Username field", "ref": "S1E2", "text": "user@example.com"}
</mcp4_browser_type>

<!-- 4. Click submit -->
<mcp4_browser_click>
{"element": "Login button", "ref": "S1E5"}
</mcp4_browser_click>

<!-- 5. Wait for navigation -->
<mcp4_browser_wait_for>
{"text": "Welcome"}
</mcp4_browser_wait_for>
```

## Network Payload Interception Strategy

The default `mcp4_browser_network_requests` only shows URLs and status codes, not full request/response payloads. To inspect AJAX payloads, use `mcp4_browser_run_code` to inject interceptors.

### Capture Request & Response Payloads

```xml
<mcp4_browser_run_code>
{
  "code": "async (page) => {
    const captured = [];
    
    // Intercept requests
    page.on('request', req => {
      if (req.url().includes('ajax') || req.url().includes('admin-ajax')) {
        captured.push({
          type: 'REQUEST',
          url: req.url(),
          method: req.method(),
          postData: req.postData()
        });
      }
    });
    
    // Intercept responses
    page.on('response', async res => {
      if (res.url().includes('ajax') || res.url().includes('admin-ajax')) {
        try {
          const body = await res.text();
          captured.push({
            type: 'RESPONSE',
            url: res.url(),
            status: res.status(),
            body: body.slice(0, 2000) // Truncate large responses
          });
        } catch (e) {
          captured.push({ type: 'RESPONSE_ERROR', url: res.url(), error: e.message });
        }
      }
    });
    
    // Now perform the action that triggers AJAX
    // e.g., await page.click('button.submit');
    
    // Wait for network activity to complete
    await page.waitForTimeout(3000);
    
    return captured;
  }"
}
</mcp4_browser_run_code>
```

### Filter by Specific AJAX Action

```xml
<mcp4_browser_run_code>
{
  "code": "async (page) => {
    const captured = [];
    
    page.on('response', async res => {
      // Filter for specific WordPress AJAX action
      if (res.url().includes('action=save_appointment')) {
        try {
          captured.push({
            url: res.url(),
            status: res.status(),
            body: await res.text()
          });
        } catch (e) {}
      }
    });
    
    // Trigger the action
    await page.click('button#save');
    await page.waitForTimeout(2000);
    
    return captured;
  }"
}
</mcp4_browser_run_code>
```

### Debug Form Submission Payloads

```xml
<mcp4_browser_run_code>
{
  "code": "async (page) => {
    let formData = null;
    
    page.on('request', req => {
      if (req.method() === 'POST' && req.url().includes('admin-ajax')) {
        formData = {
          url: req.url(),
          method: req.method(),
          postData: req.postData(),
          headers: req.headers()
        };
      }
    });
    
    // Click submit button
    await page.click('button[type=\"submit\"]');
    await page.waitForTimeout(2000);
    
    return formData;
  }"
}
</mcp4_browser_run_code>
```

### Continuous Monitoring Pattern

```xml
<!-- Step 1: Set up interceptors BEFORE navigating -->
<mcp4_browser_run_code>
{
  "code": "async (page) => {
    // Store captured data globally
    window.networkLog = [];
    
    page.on('request', req => {
      if (req.resourceType() === 'xhr' || req.resourceType() === 'fetch') {
        window.networkLog.push({ type: 'req', url: req.url(), data: req.postData() });
      }
    });
    
    page.on('response', async res => {
      if (res.request().resourceType() === 'xhr' || res.request().resourceType() === 'fetch') {
        try {
          window.networkLog.push({ type: 'res', url: res.url(), status: res.status(), body: (await res.text()).slice(0, 1000) });
        } catch (e) {}
      }
    });
    
    return 'Interceptors installed';
  }"
}
</mcp4_browser_run_code>

<!-- Step 2: Perform actions normally -->
<mcp4_browser_click>
{"element": "Submit", "ref": "button.submit"}
</mcp4_browser_click>

<!-- Step 3: Retrieve captured data -->
<mcp4_browser_evaluate>
{"function": "() => window.networkLog"}
</mcp4_browser_evaluate>
```

### When to Use This Strategy

- **Debugging AJAX failures**: See exact error messages from server
- **Verifying form data**: Check what payload is being sent
- **Understanding API flow**: Trace request/response sequence
- **Finding timing issues**: See if responses arrive out of order

