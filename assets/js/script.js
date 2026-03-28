// SLIDE BERITA //
document.addEventListener('DOMContentLoaded', function () {
  var toggleLinks = document.querySelectorAll('.toggle-text');

  toggleLinks.forEach(function (link) {
    link.addEventListener('click', function () {
      var shortText = this.previousElementSibling.previousElementSibling;
      var fullText = this.previousElementSibling;

      if (fullText.style.display === 'none') {
        shortText.style.display = 'none';
        fullText.style.display = 'block';
        this.textContent = 'Tutup kembali';
      } else {
        shortText.style.display = 'block';
        fullText.style.display = 'none';
        this.textContent = 'Baca selanjutnya';
      }
    });
  });

  var currentPage = 0;
  var pages = document.querySelectorAll('.gallery-page');
  var totalPages = pages.length;
  var galleryWrapper = document.querySelector('.gallery-wrapper');
  var prevButton = document.querySelector('.prev');
  var nextButton = document.querySelector('.next');

  function updateGallery() {
    var offset = -currentPage * 100;
    galleryWrapper.style.transform = 'translateX(' + offset + '%)';
  }

  prevButton.addEventListener('click', function () {
    if (currentPage > 0) {
      currentPage--;
      updateGallery();
    }
  });

  nextButton.addEventListener('click', function () {
    if (currentPage < totalPages - 1) {
      currentPage++;
      updateGallery();
    }
  });
});

// SLIDE NAVBAR //

function toggleNavbar() {
  var navbar = document.getElementById("navbar");
  var container = document.querySelector('.container');
  if (navbar.style.width === "250px") {
    navbar.style.width = "0";
    container.style.marginLeft = "0";
  } else {
    navbar.style.width = "250px";
    container.style.marginLeft = "250px";
  }
}



function toggleOpsi() {
  var tipe = document.getElementById('tipe_pertanyaan').value;
  document.getElementById('opsi_container').style.display = tipe == 'multiple choice' ? 'block' : 'none';
}

function addOpsi() {
  var container = document.getElementById('opsi_fields');
  var count = container.getElementsByTagName('input').length + 1;
  if (count <= 10) {
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'opsi[]';
    input.placeholder = 'Opsi ' + count;
    container.appendChild(input);
    container.appendChild(document.createElement('br'));
  }
}

function toggleEditOpsi() {
  var tipe = document.getElementById('edit_tipe_pertanyaan').value;
  document.getElementById('edit_opsi_container').style.display = tipe == 'multiple choice' ? 'block' : 'none';
}

function addEditOpsi() {
  var container = document.getElementById('edit_opsi_fields');
  var count = container.getElementsByTagName('input').length + 1;
  if (count <= 10) {
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'opsi[]';
    input.placeholder = 'Opsi ' + count;
    container.appendChild(input);
    container.appendChild(document.createElement('br'));
  }
}

