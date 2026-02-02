# Changelog

All notable changes to `tab-layout-plugin` will be documented in this file.

## 4.0.0 - 2026-02-02

### v4.0.0 Release

This major release upgrades the Filament Tab Plugin to support Filament v5, bringing enhanced compatibility and new features aligned with the latest Filament framework.

#### What's New

- Full compatibility with Filament v5
- Improved performance and stability
- Updated dependencies to match Filament v5 requirements

#### What's Changed

##### Other Changes

* Allow Filament v5 by @webard in https://github.com/solutionforest/filament-tab-plugin/pull/15

#### How to Upgrade

1. Update your `composer.json` to require `"solution-forest/tab-layout-plugin": "^4.0"`
2. Run `composer update`
3. Publish updated assets: `php artisan filament:assets`
4. If using custom themes, update your `tailwind.config.js` with the new asset paths
5. Test your tree widgets/pages for any custom overrides that may need adjustment

#### Migration Notes

- Review your model classes for any custom `determine*ColumnName()` methods and ensure they align with the new defaults
- Toolbar actions are now fully supported; update any conditional logic if previously limited
- Run `composer analyse` and `composer test` to verify compatibility

For full details, see the [commit changes](https://github.com/solutionforest/filament-tab-plugin/commit/a29f626e36076c20f4a1738cd0e3e51fb9d601a3). If you encounter issues, please check the [documentation](https://github.com/solutionforest/filament-tab-plugin#readme) or open an issue.

**Full Changelog**: https://github.com/solutionforest/filament-tab-plugin/compare/3.2.4...4.0.0

## 3.2.4 - 2025-12-29

### What's Changed in 3.2.4

#### 🚀 New features

- Merge pull request #13 from webard-playground/feature/backed-enum-icons (3c2cf2a)

#### 🔧 Other Changes

- Bump stefanzweifel/git-auto-commit-action from 6 to 7 (1152e65)
- Bump actions/checkout from 5 to 6 (af05a8c)
- allow BackedEnum in HasIcon trait (3c565fb)

### Installation

**Full Changelog**: https://github.com/solutionforest/filament-tab-plugin/compare/3.2.3...3.2.4

## 3.2.3 - 2025-09-23

<!-- Release notes generated using configuration in .github/release.yml at 3.x -->
### What's Changed

#### Other Changes

* Bump aglipanci/laravel-pint-action from 2.5 to 2.6 by @dependabot[bot] in https://github.com/solutionforest/filament-tab-plugin/pull/8
* Bump actions/checkout from 4 to 5 by @dependabot[bot] in https://github.com/solutionforest/filament-tab-plugin/pull/7
* Bump actions/checkout from 4 to 5 by @dependabot[bot] in https://github.com/solutionforest/filament-tab-plugin/pull/9
* Support to InteractsWithPageFilters  by @dmandrade in https://github.com/solutionforest/filament-tab-plugin/pull/10

### New Contributors

* @dmandrade made their first contribution in https://github.com/solutionforest/filament-tab-plugin/pull/10

**Full Changelog**: https://github.com/solutionforest/filament-tab-plugin/compare/3.2.2...3.2.3

## 3.2.2 - 2025-08-13

### What's Changed in 3.2.2

#### 🔧 Other Changes

* chore: change minimum stability from beta to stable in composer.json

### Installation

```bash
composer require solution-forest/tab-layout-plugin:^3.2.2




```
## 3.2.1 - 2025-08-11

### What's Changed in 3.2.1

#### 🐛 Bug Fixes

- fix: Update MakeTabWidgetCommand to ensure the widget can create inside the filament panel/ resource
- fix: Unable to find `TabsWidget` widget

### Installation

```bash
composer require solution-forest/tab-layout-plugin:^3.2.1





```
## 3.2.0 - 2025-08-01

<!-- Release notes generated using configuration in .github/release.yml at 3.2.0 -->
✨ New Feature

- Refactor Tabs component to conditionally apply 'contained' class

**Full Changelog**: https://github.com/solutionforest/filament-tab-plugin/compare/3.1.0...3.2.0
