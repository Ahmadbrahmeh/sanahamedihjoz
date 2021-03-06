$imagesExtList = "gif,jpg,jpeg,png";
$videosExtList = "mov,avi,mp4";

function getFile($element)  {
  $file = $element.files[0];
  return $file;
}

//like jpg,gif,png
function getAllowedExtenstions($extentions) {
  $extnArray = $extentions.split(",");
  //trim extentions
  $extnArray.forEach(function(item, index, arr) {
    arr[index] = item.trim().toString().toLowerCase();
  });
  return $extnArray;
}

//check if file extention valid
function validateAllowedExtention($file, $extentions) {
  $file = getFile($file);
  $extnArray = getAllowedExtenstions($extentions);
  $fileExtention = getExt($file.name);
  return $extnArray.includes($fileExtention);
}

function isValidImage($fileElement, errorFunction) {
  var _URL = window.URL || window.webkitURL;
  var $file, img;
  if (($file = $fileElement.files[0])) {
    img = new Image();

    img.onload = function () {
      $fileElement.width;
      $fileElement.height;
    };

    img.onerror = errorFunction;
    img.src = _URL.createObjectURL($file);
  }
}

function validateFileSize($file, $maxSize) {
  $file = getFile($file);
  size = $file.size;
  if (size > getBytes($maxSize))
      return false;
  return true;
}

function getBytes($size){
  $sizeCompact = $size.match(/[\d\.]+|\D+/g);
  $sizeNumber = $sizeCompact[0];
  $sizeUnit = $sizeCompact[1].toString().toUpperCase();

  if($sizeUnit == "M" || $sizeUnit == "MB"){
    return $sizeNumber * 1024 * 1024;
  } else if($sizeUnit == "KB") {
    return $sizeNumber * 1024;
  }
  else if ($sizeUnit == "B") {
   return $sizeNumber; 
  }

return Number.MAX_SAFE_INTEGER;
}

function getExt(filename) {
  return /[^.]+$/.exec(filename).toString().toLowerCase();
}

function isImage($file_name) {
  $fileExtention = getExt($file_name);
  return $imagesExtList.includes($fileExtention);
}

function isVideo($file_name) {
  $fileExtention = getExt($file_name);
  return $videosExtList.includes($fileExtention);
}