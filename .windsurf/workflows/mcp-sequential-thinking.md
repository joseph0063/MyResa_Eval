---
description: Use the sequential-thinking MCP server for complex problem solving.
---

# Sequential Thinking MCP Server

Break down complex problems through a structured thinking process with hypothesis generation and verification.

## Available Tools

### `mcp6_sequentialthinking`

Process complex problems through sequential thought steps.

**Parameters:**
- `thought` (required): Your current thinking step
- `nextThoughtNeeded` (required): true if more thinking needed
- `thoughtNumber` (required): Current thought number (1-indexed)
- `totalThoughts` (required): Estimated total thoughts needed
- `isRevision` (optional): true if revising previous thinking
- `revisesThought` (optional): Which thought number is being reconsidered
- `branchFromThought` (optional): Branching point thought number
- `branchId` (optional): Branch identifier
- `needsMoreThoughts` (optional): true if more thoughts needed at end

## Examples

### Basic Sequential Thinking

```xml
<!-- Thought 1: Define the problem -->
<mcp6_sequentialthinking>
{
  "thought": "I need to debug why the login form fails. First, let me identify the components involved: frontend form, AJAX handler, and backend authentication.",
  "nextThoughtNeeded": true,
  "thoughtNumber": 1,
  "totalThoughts": 4
}
</mcp6_sequentialthinking>

<!-- Thought 2: Analyze -->
<mcp6_sequentialthinking>
{
  "thought": "The error occurs after form submission. The AJAX request returns 500. This suggests a backend issue, not frontend validation.",
  "nextThoughtNeeded": true,
  "thoughtNumber": 2,
  "totalThoughts": 4
}
</mcp6_sequentialthinking>

<!-- Thought 3: Hypothesis -->
<mcp6_sequentialthinking>
{
  "thought": "Hypothesis: The backend authentication handler is throwing an exception due to missing database connection or invalid credentials check.",
  "nextThoughtNeeded": true,
  "thoughtNumber": 3,
  "totalThoughts": 4
}
</mcp6_sequentialthinking>

<!-- Thought 4: Conclusion -->
<mcp6_sequentialthinking>
{
  "thought": "Verification: Checked the error logs and found a PDOException. The database credentials in wp-config.php are incorrect. Solution: Update DB_PASSWORD constant.",
  "nextThoughtNeeded": false,
  "thoughtNumber": 4,
  "totalThoughts": 4
}
</mcp6_sequentialthinking>
```

### Revising a Previous Thought

```xml
<mcp6_sequentialthinking>
{
  "thought": "I realize my earlier assumption was wrong. The error is not in authentication but in session handling. Let me reconsider.",
  "nextThoughtNeeded": true,
  "thoughtNumber": 5,
  "totalThoughts": 6,
  "isRevision": true,
  "revisesThought": 3
}
</mcp6_sequentialthinking>
```

### Branching to Explore Alternative

```xml
<mcp6_sequentialthinking>
{
  "thought": "Let me explore an alternative hypothesis: the issue might be CORS-related rather than backend authentication.",
  "nextThoughtNeeded": true,
  "thoughtNumber": 4,
  "totalThoughts": 6,
  "branchFromThought": 2,
  "branchId": "cors-investigation"
}
</mcp6_sequentialthinking>
```

### Extending When More Thoughts Needed

```xml
<mcp6_sequentialthinking>
{
  "thought": "I've reached my initial estimate but need more analysis. The problem is more complex than expected.",
  "nextThoughtNeeded": true,
  "thoughtNumber": 4,
  "totalThoughts": 6,
  "needsMoreThoughts": true
}
</mcp6_sequentialthinking>
```

## When to Use

- **Complex debugging**: Multi-step investigation of bugs
- **Architecture decisions**: Weighing trade-offs systematically
- **Planning**: Breaking down large tasks into steps
- **Analysis**: Understanding unfamiliar codebases
- **Problem solving**: When the solution isn't immediately clear

## Best Practices

1. Start with an initial estimate but be ready to adjust `totalThoughts`
2. Mark thoughts that revise previous thinking with `isRevision`
3. Use branching to explore alternative approaches
4. Express uncertainty when present
5. Only set `nextThoughtNeeded: false` when truly satisfied with the answer
