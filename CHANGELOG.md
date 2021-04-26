# Changelog

All notable changes to `spotlight` will be documented in this file.

## Unreleased
- Add call to `shouldBeShown` method on the `SpotlightCommand` for any custom logic needed for determining whether a command should be shown in the Spotlight component. This includes resolving any dependencies out of the Laravel service container.

## 0.1.6
- Add `SpotlightCommandDependency` type support. Defaults to `SpotlightCommandDependency::SEARCH`
- *Important* The dependency order was incorrect, you need to change your dependency order after updating if you have more than one dependency.

## 0.1.5
- Add option to toggle Spotlight via browser events. `$this->dispatchBrowserEvent('toggle-spotlight');`

## 0.1.4
- Add `make:spotlight` command.

## 0.1.3
- Add `registerCommandIf` and `registerCommandUnless` helpers to register commands conditionally.
- Fix element flashing by applying x-cloak
- Reset Spotlight state on close.

## 0.1.2
- Add conditional CSS for non TailwindCSS users.
- Add option to customize keyboard shortcut.

## 0.1.1
- Upgrade Laravel Mix.

## 0.1.0
- Initial release.
