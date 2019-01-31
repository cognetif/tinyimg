# Cognetif TinyImg

## Description
Similar to the app: perch_kraken, but uses the TinyPNG/TinyJPG API to optimize your uploaded images.
TinyPNG/TinyJPG offers their service for free for the first 500 optimizations each month.

## Features
1. 2 operation modes configurable via Perch Settings: `On Upload` or `CRON`
1. Queue table shows image, original file size, tiny file size, graphical savings percent bar, and ReQueue options.
1. TinyImage Options page allows for manual processing of the queue and a queue cleaning function to purge data if files have
been deleted from the server.
  

## Installation
1. Add cognetif_tinyimg to `addons/apps/` folder and `apps.php`
1. Create an API Key at: https://tinyjpg.com/developers
1. Add your API Key in the Tinify API Key settings on the main Perch Settings page.

# Usage
1. You can find the settings for the app in the Perch Settings page
1. There is a new app TinyImage in the main side menu available which you can use to access the queue page and options page.
1. From the queue page, you can see how many images are in the queue and for those that are done, what % file size reduction has been accomplished.
1. You can also re-queue an image if something does not seem right.
1. From the options page you can manually run the queue or clean the queue if images have been deleted from the assets system. 
1. If you have the Perch Scheduled Tasks setup and running, the queue will automatically get cleaned each day.

## Upload vs CRON mode
In the Perch settings page, you can specify if you want to optimize the images in the queue directly on upload or via a scheduled task.

If you select the "Upload" mode, they will be optimized as soon as a user uploads the image but the upload request will take longer.  

Optimizing with "CRON" mode will not hinder the upload request, but unoptimized images will be served until the scheduled task completes and optimizes all the images in the queue.

On the Perch settings page, you can specify the CRON frequency in minutes.

If you are using the "CRON" mode, you must have setup the Perch scheduled tasks as documented.

## License
This project is free, open source, and GPL friendly. You can use it for commercial projects, open source projects, or really almost whatever you want.

## Donations
This is free software but it took some time to develop.  If you use it, please send me a message I'd be interested to know which site uses it. If you appreciate the app and use it regularly, feel free to [Buy me a Beer](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6EBCDCCZRNSWW&source=url)!

## Issues
Create a GitHub Issue: https://github.com/cognetif/tinyimg or better yet become a contributor.

## Developper
Cognetif : Jordin Brown jbrown@cognetif.com
