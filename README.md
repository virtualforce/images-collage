# Make images collage using PHP GD library and MySQL

In this tutorial, I will guide how to make the images collage using PHP GD library and MySQL.

## Configuration

### Step 1:
Import the Db schema from folder "db->imagecollage.sql"

### Step 2:
Set the DB name and credentials in connection.php file

### Step 3:
Make the virtual host, to run the site as a domain on the local machine like **imagecollage.dev** or **localhost.imagecollage.com** etc.


That's it :) you are good to go.

Run the **imagecollage.dev** or **localhost.imagecollage.com** in your favorite browser as mine is chrome.

## Componets

1. functions.php
2. pics (folder)
3. master.jpg

These 3 components are whole collage soul.

## How it works
### 1- functions.php
functions.php file contains all the functions do the following functionality

1. Insert the positions of images in the DB.
2. Make the collage using images position from DB and pictures from **pics** folder


Here is list of functions

1. readMasterImageAndSetPositionsInDb()
2. makeCollage()
3. getRandomEmptyPosition()
4. getOccupiedPositions()
5. getPastedImages()
6. insertPosition()
7. saveNewImage($params)
8. lastedImages()
9. getFileExtension($path)
10. scanFolderAndAddTheImagesInDb()

### 2- pics (folder)
In this folder all the pictures of collage, those will be posted on the master picture would be here.

### 3- master.jpg
This is the base of collage, all the pictures from **pics** folder would be paste on this and thus the way collage will be created.

## Things should be in mind

1. Pictures name in DB and in **pics** folder should be same
2. In functions.php there are two constants named **X** and **Y**. These constants are responsible for height and width of the images on collage and also the number of images on collage. Increase the **X** and **Y** values will decrease the images on the collage and vice versa.  
