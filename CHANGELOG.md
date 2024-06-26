# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


## [3.1] - 2020-03-09
### Changed
- Changed all class properties to be static typed

## [3.0] - 2020-03-09
### fixed
- Add disclaimer for older versions being unsupported
- Rename RulesInterface::addRules to getRules
- Bump copyright to 2021
- Fix exception message grammar in EmailValidator

## [2.1] - 2020-02-14
### fixed
- Removed deprecated usage of test framework OM
- Cleaned up FQN

## [2.0.2] - 2020-10-30
### fixed
- Fixed failing ValidatorBuilderTest

## [2.0.1] - 2020-10-03
### Added
- License field
### Changed
- Improved example configuration in the README

## [2.0.0] - 2020-08-22
### Added
- Translation capability for validator message
### Fixed
- Compatibility with Magento 2.4
- Compatibility with PHPUnit 9 and prophecy

## [1.2.0] - 2020-08-22
### Added
- Backported translation for validator messages

## [1.1.0] - 2020-08-22
### Added
- Custom ValidatorException
- LICENSE file
### Changed
- Main plugins now throw ValidatorException

## [1.0.0] - 2020-08-18
### Added
- Compatibility chart
### Changed
- Disable all configuration by default
- Move configuration location to the advanced tab
- Update readme for initial stable release

## [0.4.0] - 2020-06-25
### Added
- Added more Unit tests for uncovered classes
### Changed
- Move magento module dependencies to composer.json
- Refractor existing unit test to improve code quality
- Change php dependency to latest supported
- Remove dependency on Zend base class for validator classes

## [0.3.0] - 2020-06-25
### Added
- Added unit tests for validator
- improved code quality

## [0.2.0] - 2020-02-04
### Added
- Moved license to MIT instead of GPLv3
- Added keywords to composer.json

