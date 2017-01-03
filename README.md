# fsapi-remote

**This code is work in progress! It is not finnished yet, feel free co contribute to it.**

This Code shows an example implementation of the fsapi (Frontier Silicon API for PHP):

https://github.com/flammy/fsapi

In this example I created an radio-gui (website) with xajax, jquery and bootstrap.

If you have any feature requests or found some bugs, please open an issue.

## Download & Install

You can choose between multiple ways to get the code.
Before downloading / cloning the code choose a directory within a running webserver, which can handle php, to put your code in.

### git 
You can clone the repository and build the dependencies by your self using composer.

```bash
  git clone https://github.com/flammy/fsapi-remote.git
  cd fsapi-remote
  composer install
```
### download release 
You can download the code of an release and build the dependencies by your self using composer.

```bash
  wget https://github.com/flammy/fsapi-remote/archive/2.0.zip
  unzip 2.0.zip
  mv 2.0 fsapi-remote
  cd fsapi-remote
  composer install
```

### download release artifact

You can download an prebuild artifact of every release on the release page.
Prebuild artifacts contain all dependencies so no further steps are required.

```bash
  wget https://github.com/flammy/fsapi-remote/releases/download/2.0/fsapi-remote-2.0.zip
  unzip fsapi-remote-2.0.zip
  mv fsapi-remote-2.0 fsapi-remote
```


## Configuration

After the installation you can open the page in the browser and add your device with the setup button in the navigation bar.

## troubleshooting

Make sure your webserver has write-access to the following files and folders


 - /config.txt
 - vendor/xajax/xajax/xajax_js/deferred/

If they dont get created automaticaly you can create them manualy.

```bash
  cd fsapi-remote
  touch ./config.txt
  chown www-data:www-data config.txt 
  mkdir ./vendor/xajax/xajax/xajax_js/deferred/
  chown www-data:www-data ./vendor/xajax/xajax/xajax_js/deferred/
```
Please keep in mind to replace www-data with the user & group your webserver is running within.
