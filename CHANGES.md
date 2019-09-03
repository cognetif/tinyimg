# Changelog
All notable changes to this project will be documented in this file.

## [v1.0.0] - 2019-02-04
### Added
- Initial creation of project.
- Image optimization via Tinify API service
- Setting to run optimizations on image upload or via CRON.
- Image optimization queue page with status, progress and requeue options.

## [v1.2.0] - 2019-09-03
### Added
- Setting to choose how to treat original file (compress or no compression)
- Some fun emoji to the queue page.
- New DB column for percentage saved


### Changed
- Flattened folder structure, removing perch default structure `/addons/app/cognetif_tinyimg`.
- Recommended installation now via git submodule. Refer to [README.md](README.md).
- Queue page saved percentage bar is right formatted now, and makes it a little clearer as a visualization.

### FIXED
- [Issue #1](https://github.com/cognetif/tinyimg/issues/1) : Percentage is now properly reported and formatted.
- [Issue #3](https://github.com/cognetif/tinyimg/issues/3) : Setting added to choose how to handle original image was added. 

