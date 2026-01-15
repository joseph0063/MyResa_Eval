---
description: Interact with the playwright-isolated MCP server for isolated browser automation.
---

# Playwright Isolated MCP Server

Browser automation with isolated sessions. Same capabilities as the regular Playwright MCP but with session isolation.

## Available Tools

All tools have the same parameters as the regular Playwright MCP, but use `mcp5_` prefix.

### `mcp5_browser_navigate`

Navigate to a URL.

```xml
<mcp5_browser_navigate>
{"url": "https://www.google.com"}
</mcp5_browser_navigate>
```

---

### `mcp5_browser_snapshot`

Capture accessibility snapshot.

```xml
<mcp5_browser_snapshot />
```

---

### `mcp5_browser_click`

Click on an element.

```xml
<mcp5_browser_click>
{"element": "Submit button", "ref": "button[name='submit']"}
</mcp5_browser_click>
```

---

### `mcp5_browser_type`

Type text into an element.

```xml
<mcp5_browser_type>
{"element": "Search input", "ref": "input[name='q']", "text": "hello world", "submit": true}
</mcp5_browser_type>
```

---

### `mcp5_browser_fill_form`

Fill multiple form fields.

```xml
<mcp5_browser_fill_form>
{
  "fields": [
    {"name": "Username", "ref": "input#username", "type": "textbox", "value": "john"},
    {"name": "Password", "ref": "input#password", "type": "textbox", "value": "secret"}
  ]
}
</mcp5_browser_fill_form>
```

---

### `mcp5_browser_select_option`

Select dropdown option.

```xml
<mcp5_browser_select_option>
{"element": "Country dropdown", "ref": "select#country", "values": ["US"]}
</mcp5_browser_select_option>
```

---

### `mcp5_browser_hover`

Hover over an element.

```xml
<mcp5_browser_hover>
{"element": "Menu item", "ref": "li.menu-item"}
</mcp5_browser_hover>
```

---

### `mcp5_browser_drag`

Drag and drop.

```xml
<mcp5_browser_drag>
{
  "startElement": "Draggable item", "startRef": "div.draggable",
  "endElement": "Drop zone", "endRef": "div.dropzone"
}
</mcp5_browser_drag>
```

---

### `mcp5_browser_press_key`

Press a keyboard key.

```xml
<mcp5_browser_press_key>
{"key": "Enter"}
</mcp5_browser_press_key>
```

---

### `mcp5_browser_take_screenshot`

Take a screenshot.

```xml
<mcp5_browser_take_screenshot>
{"fullPage": true, "filename": "screenshot.png"}
</mcp5_browser_take_screenshot>
```

---

### `mcp5_browser_wait_for`

Wait for text or time.

```xml
<mcp5_browser_wait_for>
{"text": "Loading complete"}
</mcp5_browser_wait_for>

<mcp5_browser_wait_for>
{"time": 2}
</mcp5_browser_wait_for>
```

---

### `mcp5_browser_navigate_back`

Go back.

```xml
<mcp5_browser_navigate_back />
```

---

### `mcp5_browser_file_upload`

Upload files.

```xml
<mcp5_browser_file_upload>
{"paths": ["/path/to/file.pdf"]}
</mcp5_browser_file_upload>
```

---

### `mcp5_browser_handle_dialog`

Handle dialogs.

```xml
<mcp5_browser_handle_dialog>
{"accept": true}
</mcp5_browser_handle_dialog>
```

---

### `mcp5_browser_console_messages`

Get console messages.

```xml
<mcp5_browser_console_messages>
{"level": "error"}
</mcp5_browser_console_messages>
```

---

### `mcp5_browser_network_requests`

Get network requests.

```xml
<mcp5_browser_network_requests />
```

---

### `mcp5_browser_tabs`

Manage tabs.

```xml
<mcp5_browser_tabs>
{"action": "list"}
</mcp5_browser_tabs>
```

---

### `mcp5_browser_resize`

Resize browser.

```xml
<mcp5_browser_resize>
{"width": 1920, "height": 1080}
</mcp5_browser_resize>
```

---

### `mcp5_browser_evaluate`

Execute JavaScript.

```xml
<mcp5_browser_evaluate>
{"function": "() => document.title"}
</mcp5_browser_evaluate>
```

---

### `mcp5_browser_run_code`

Run Playwright code.

```xml
<mcp5_browser_run_code>
{"code": "async (page) => { return await page.title(); }"}
</mcp5_browser_run_code>
```

---

### `mcp5_browser_close`

Close browser.

```xml
<mcp5_browser_close />
```

---

### `mcp5_browser_install`

Install browser.

```xml
<mcp5_browser_install />
```

## When to Use Isolated vs Regular

- **playwright-isolated (mcp5)**: Use when you need a fresh browser session that doesn't share state
- **playwright (mcp4)**: Use for persistent sessions where you want to maintain cookies/state

## Network Payload Interception Strategy

The default `mcp5_browser_network_requests` only shows URLs and status codes, not full request/response payloads. To inspect AJAX payloads, use `mcp5_browser_run_code` to inject interceptors.

### Capture Request & Response Payloads

```xml
<mcp5_browser_run_code>
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
</mcp5_browser_run_code>
```

### Filter by Specific AJAX Action

```xml
<mcp5_browser_run_code>
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
</mcp5_browser_run_code>
```

### Debug Form Submission Payloads

```xml
<mcp5_browser_run_code>
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
</mcp5_browser_run_code>
```

### Continuous Monitoring Pattern

```xml
<!-- Step 1: Set up interceptors BEFORE navigating -->
<mcp5_browser_run_code>
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
</mcp5_browser_run_code>

<!-- Step 2: Perform actions normally -->
<mcp5_browser_click>
{"element": "Submit", "ref": "button.submit"}
</mcp5_browser_click>

<!-- Step 3: Retrieve captured data -->
<mcp5_browser_evaluate>
{"function": "() => window.networkLog"}
</mcp5_browser_evaluate>
```

### When to Use This Strategy

- **Debugging AJAX failures**: See exact error messages from server
- **Verifying form data**: Check what payload is being sent
- **Understanding API flow**: Trace request/response sequence
- **Finding timing issues**: See if responses arrive out of order
