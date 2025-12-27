# ZedCMS

A lightweight, drop-in CMS for shared hosting — built to escape WordPress bloat without becoming a framework.

ZedCMS is an opinionated, minimal CMS written for people who want to upload files via FTP, edit content in a modern block editor, and ship fast — without Composer, SSH, containers, or framework ceremony.

This is not a general-purpose PHP framework.
If you want Symfony, Laravel, or Filament — those projects already exist and do that job extremely well.

## Why ZedCMS exists

After years of working with WordPress, I wanted something that:

- Runs on cheap shared hosting
- Has a modern editing experience (blocks, not HTML blobs)
- Avoids 200k+ lines of legacy code
- Is easy to read, hack, and extend without tooling
- Does not require Composer, build pipelines, or CI to get started

ZedCMS optimizes for runtime simplicity and deployability, not enterprise-scale abstractions.

## Design Philosophy (Important)

ZedCMS intentionally trades framework orthodoxy for practical constraints.

### What ZedCMS optimizes for

- FTP-based deployment
- Zero build steps
- Minimal bootstrap cost
- Small, readable core
- Low barrier for theme & plugin authors
- Works on shared hosting without SSH

### What ZedCMS intentionally does not optimize for

- Large teams
- Long-lived enterprise codebases
- Dependency injection containers
- PSR-heavy abstractions
- Composer-managed ecosystems

If your baseline expectation is:
> "No Composer + no CI = unusable"

Then ZedCMS is not for you, and that’s okay.

## Architecture Overview

### Micro-Kernel Core (~500 lines)

The core handles only:
- Request lifecycle
- Authentication
- Event dispatching
- Addon bootstrapping

Everything else — including the admin panel — is an addon.

### Event-Driven Routing (No Static Route Maps)

Instead of static route definitions, addons listen for a `route_request` event and dynamically claim URIs.

**Why?**
- Keeps the core unaware of features
- Allows addons to fully own their routes
- Enables drop-in extensibility without central configuration

**Tradeoff:**
Addons are currently loaded before final 404 resolution. This is a known cost and an active area of optimization (two-phase bootstrapping is planned).

### Content Storage (Blocks, Not HTML)

Content is stored as structured JSON blocks, not raw HTML.

- **Editor:** React 18 + BlockNote (ProseMirror-based)
- **Storage:** JSON via MySQL (PDO)
- **Rendering:** Server-side block renderer

**Benefits:**
- Safer rendering
- Easier migrations
- Better long-term portability

### Built-in Image Pipeline

Image optimization is part of the core:
- Automatic WebP conversion
- Resizing & thumbnail generation on upload
- No plugins required

### Built-in Developer Docs

The admin panel includes a developer wiki that:
- Reads Markdown files directly
- Lives inside the project
- Requires no external tooling

## Development Decisions

### Why no Composer?

This is intentional.

ZedCMS is designed to be:
- Uploaded via FTP
- Installed without SSH
- Run on shared hosting environments

Composer-based workflows assume tooling availability that many CMS users simply don’t have.
This choice sacrifices ecosystem size in favor of deployability and approachability.

### About globals, statics, and facades

You will see:
- Static APIs
- Facade-style helpers
- Some global access patterns

This is a deliberate API design choice to:
- Keep theme/plugin development simple
- Reduce cognitive load for non-framework developers

Internally, a context registry is being introduced to reduce reliance on globals — while keeping the public API stable.

### Code Style & Conventions

ZedCMS favors:
- Readability over abstraction
- Explicit arrays over deep object graphs
- Simple control flow over indirection

It does not currently include:
- Automated tests
- CI workflows
- Static analysis

These are planned, but architecture and usability were prioritized first.

## When should you use ZedCMS?

**ZedCMS is a good fit if you want:**
- A WordPress alternative without WordPress baggage
- A modern block editor with simple PHP theming
- A CMS you can fully understand in a weekend
- Something hackable without learning a framework

## When you should NOT use ZedCMS

**Do not use ZedCMS if:**
- You want a full PHP framework
- You need enterprise-scale guarantees
- You expect PSR/DI-first architecture
- You want a large plugin ecosystem today

## AI Transparency

To be fully transparent: **Artificial Intelligence was utilized in the creation of ZedCMS.**

This project was built with the assistance of advanced AI coding agents to accelerate development, refactor legacy code, and ensure modern best practices were followed—all while strictly adhering to the "no-framework" philosophy. The use of AI allows ZedCMS to be ambitious in its features while remaining a lightweight, maintainable tool for one-person teams and shared hosting environments.

## Tech Stack

- PHP 8.2+
- MySQL (PDO + JSON)
- React 18
- Vite
- BlockNote

## Status

ZedCMS is early-stage and evolving.
The architecture is stable; ergonomics and tooling are still improving.

Constructive feedback — especially around routing, performance, and extensibility — is welcome.

## License

MIT
