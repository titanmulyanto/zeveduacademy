# Zevedu Academy - Claude Code Configuration

## Project Overview
- **Project**: Zevedu Academy - E-Learning Platform
- **Framework**: CodeIgniter 4
- **Location**: C:\xampp\htdocs\zeveduacademy

## Installed Skills

### TypeUI (@bergside/typeui)
Design skills for UI generation. Location: `C:\Users\LENOVO\.claude\skills\typeui`

**Usage**:
- Reference design patterns from TypeUI
- Use design skills for consistent UI
- Browse: https://www.typeui.sh/design-skills

### GStack (@garrytan/gstack)
AI Development Workflow Suite. Location: `C:\Users\LENOVO\.claude\skills\gstack`

**Available Skills**:
- `/office-hours` - Product brainstorming
- `/plan-ceo-review` - Scope evaluation
- `/plan-eng-review` - Technical design
- `/review` - Code review
- `/qa` - Browser testing
- `/cso` - Security audit
- `/ship` - Deploy changes

**Usage**: Run these slash commands in Claude Code

## Context7 MCP
Real-time documentation access. VSCode Extension: `upstash.context7-mcp-1.0.1`

## Database
- **Name**: db_zevedu
- **Location**: XAMPP MySQL

## Key Directories
```
app/
├── Controllers/     # Application controllers
├── Models/         # Database models
├── Views/          # View templates
├── Config/         # Configuration files
└── Filters/       # Auth & role filters

public/
├── assets/css/     # Stylesheets
└── assets/js/     # JavaScript files
```

## Common Tasks

### Build CSS
```bash
npm run build
```

### Development Mode
```bash
npm run watch
```

## Development Notes
- Clean URL enabled (no index.php in URL)
- Uses Tailwind CSS + DaisyUI
- Phosphor Icons for UI elements
