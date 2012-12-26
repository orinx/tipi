var holder;
var progress;
var fileupload;
var tests = {
      filereader: typeof FileReader != 'undefined',
      dnd: 'draggable' in document.createElement('span'),
      formdata: !!window.FormData,
      progress: "upload" in new XMLHttpRequest
    }
var acceptedTypes = {
      'image/png': true,
      'image/jpeg': true,
      'image/gif': true
}
window.onload = function(){
  holder = document.getElementById('holder');
  progress = document.getElementById('uploadprogress'),
  fileupload = document.getElementById('upload');
  holder.addEventListener("dragexit", dragExit, false);
  document.getElementById('draghere').onmouseover = function() { this.innerHTML = "click me to upload your tiny picture ! "; }
  document.getElementById('draghere').onmouseout = function() { this.innerHTML = " drag & drop to upload your tiny picture !"; }
    if (tests.dnd) {
        holder.ondragover = function () { this.className = 'dragin'; return false; };
        holder.ondragend = function () { this.className = ''; return false; };
        holder.ondrop = function (e) {
        e.preventDefault();
        readfiles(e.dataTransfer.files);
      }
    } else {
      fileupload.className = 'hidden';
      fileupload.querySelector('input').onchange = function () {
        readfiles(this.files);
      };
    }
   var fileSelect = document.getElementById("draghere"),
    fileElem = document.getElementById("fileElem");
   fileSelect.addEventListener("click", function (e) {
      if (fileElem) {
          fileElem.click();
        }
      e.preventDefault(); // prevent navigation to "#"
    }, false);
}

function previewfile(file) {
  if (tests.filereader === true && acceptedTypes[file.type] === true) {
    var reader = new FileReader();
    reader.onload = function (event) {
      var image = new Image();
      image.src = event.target.result;
      image.width = 250; // a fake resize
      image.heigh = 250; // fake another size
      holder.appendChild(image);
    };

    reader.readAsDataURL(file);
  }  else {
    holder.innerHTML += '<p>Uploaded ' + file.name + ' ' + (file.fileSize ? (file.fileSize/1024|0) + 'K' : '');
    console.log(file);
  }
}

function readfiles(files) {
    holder.className = '';
    document.getElementById('draghere').className = 'hide';
    holder.className = 'extend';
    progress.className='';

    var formData = tests.formdata ? new FormData() : null;
    for (var i = 0; i < files.length; i++) {
      if (tests.formdata) formData.append('file'+i, files[i]);
      previewfile(files[i]);
    }

    // now post a new XHR request
    if (tests.formdata) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', './data.php');
      xhr.onload = function() {
        progress.value = progress.innerHTML = 100;
        var result = JSON.parse(xhr.responseText);
        console.log(result);
      };

      if (tests.progress) {
        xhr.upload.onprogress = function (event) {
          if (event.lengthComputable) {
            var complete = (event.loaded / event.total * 100 | 0);
            progress.value = progress.innerHTML = complete;
          }
        }
      }

      xhr.send(formData);
    }
}
function dragExit() { holder.className=''; return false; };

