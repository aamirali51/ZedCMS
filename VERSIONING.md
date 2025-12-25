# Zed CMS Versioning Policy

**Effective Date:** 2024-12-25  
**Version:** 1.0

Zed CMS follows [Semantic Versioning 2.0.0](https://semver.org/) to provide predictable, professional API evolution.

---

## Version Format: MAJOR.MINOR.PATCH

Example: `3.2.1`
- **MAJOR** = 3 (breaking changes)
- **MINOR** = 2 (new features, backward compatible)
- **PATCH** = 1 (bug fixes, backward compatible)

---

## Version Types

### MAJOR Version (e.g., 3.0.0 → 4.0.0)

**When Released:**
- Breaking changes to stable APIs
- Removal of deprecated features
- Database schema changes
- Major architectural changes

**What You Can Expect:**
- ⚠️ **Breaking changes allowed**
- 📋 Comprehensive migration guide
- 🕐 6+ month advance notice
- ✅ All deprecations clearly documented

**Upgrade Recommendation:**
- Read migration guide carefully
- Test in staging environment
- Update deprecated code first
- Plan for potential downtime

**Example Changes:**
```php
// v3.x (deprecated)
zed_old_function($arg1, $arg2);

// v4.0 (old function removed)
zed_new_function($arg1); // Must migrate
```

---

### MINOR Version (e.g., 3.0.0 → 3.1.0)

**When Released:**
- New features added
- New APIs introduced
- Performance improvements
- Non-breaking enhancements

**What You Can Expect:**
- ✅ **100% backward compatible**
- 🎉 New functionality available
- 📝 Optional new APIs
- 🔄 Deprecation notices (still works)

**Upgrade Recommendation:**
- ✅ **Safe to upgrade immediately**
- No code changes required
- Review new features
- Check deprecation warnings

**Example Changes:**
```php
// v3.0 - Still works
zed_register_menu($args);

// v3.1 - New feature added
zed_register_admin_menu($args); // New, better API

// Both work! No breaking changes.
```

---

### PATCH Version (e.g., 3.0.0 → 3.0.1)

**When Released:**
- Bug fixes only
- Security patches
- Documentation updates
- Performance fixes

**What You Can Expect:**
- ✅ **100% backward compatible**
- 🐛 Bugs fixed
- 🔒 Security improved
- 📚 Docs updated

**Upgrade Recommendation:**
- ✅ **Always safe to upgrade**
- ✅ **Recommended for security**
- No code changes needed
- Zero breaking changes

---

## Deprecation Timeline

We provide a **minimum 6-month deprecation period** before removing any stable API.

### Timeline Example

```
v3.0.0 (Jan 2024)
  ↓ Function introduced: zed_new_function()

v3.2.0 (Jun 2024)
  ↓ Old function deprecated: zed_old_function()
  ↓ Warning: "Use zed_new_function() instead"
  ↓ Still works normally

v3.3.0 (Sep 2024)
  ↓ Still deprecated, still works
  ↓ Warnings continue

v3.4.0 (Dec 2024)
  ↓ Still deprecated, still works
  ↓ Migration guide available

v4.0.0 (Jan 2025)
  ↓ Old function removed
  ↓ Breaking change (major version)
  ↓ Must migrate to zed_new_function()
```

**Minimum Timeline:**
- **Deprecation Notice:** v3.2.0 (June)
- **Removal:** v4.0.0 (January, 6+ months later)

---

## Our Promises

### We Promise:

✅ **No breaking changes in MINOR versions**
- Your code will not break on 3.x → 3.y upgrades

✅ **6+ month deprecation period**
- Minimum 2 minor versions before removal

✅ **Clear migration guides**
- Step-by-step instructions for all breaking changes

✅ **Semantic versioning strictly followed**
- Version numbers have meaning you can trust

✅ **Deprecation warnings in debug mode**
- See what needs updating before it breaks

---

## You Can Expect:

✅ **Safe minor version upgrades**
- 3.0 → 3.1 → 3.2 always safe

✅ **Advance warning of breaking changes**
- Deprecation notices 6+ months early

✅ **Time to update your code**
- No surprise breakage

✅ **Professional, predictable platform**
- Plan upgrades with confidence

---

## Breaking Change Policy

### What Counts as Breaking:

❌ Removing a stable function
❌ Changing stable function signature
❌ Removing stable event
❌ Changing stable event data structure
❌ Removing stable configuration option
❌ Database schema changes

### What's NOT Breaking:

✅ Adding new optional parameters
✅ Adding new functions
✅ Adding new events
✅ Deprecating (but not removing) features
✅ Bug fixes that restore intended behavior
✅ Performance improvements

---

## Version Support

### Current Version: 3.1.0

| Version | Status | Support | End of Life |
|---------|--------|---------|-------------|
| 3.x | **Current** | ✅ Full support | TBD |
| 2.x | Legacy | ⚠️ Security only | Jun 2025 |
| 1.x | Unsupported | ❌ No support | Dec 2023 |

**Recommendation:** Always use the latest 3.x version

---

## Checking Your Version

```php
// In code
echo ZED_VERSION; // "3.1.0"

// In config.php
define('ZED_VERSION', '3.1.0');
```

---

## Questions?

**Q: Can I stay on 3.x forever?**  
A: Yes! We'll support 3.x with security patches for 12+ months after 4.0 release.

**Q: How do I know what will break in 4.0?**  
A: Enable debug mode and check deprecation warnings. Everything deprecated in 3.x will be removed in 4.0.

**Q: What if a stable API breaks in a minor version?**  
A: That's a bug! Report it and we'll fix it or provide a migration path.

**Q: Can I use experimental APIs in production?**  
A: Not recommended. They may change without notice. Use stable APIs for production.

---

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for detailed version history.

---

**This policy is effective as of Zed CMS 3.1.0 and applies to all future versions.**
