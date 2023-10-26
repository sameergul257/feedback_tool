<div id="userTagDropdown" style="display: none;">
  <ul id="userSuggestions">
      <!-- User suggestions will be added here dynamically -->
  </ul>
</div>

<script src="{!! asset('/js/tinymce/tinymce.min.js') !!}"></script>
<script>
  tinymce.init({
    selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
    plugins: 'table lists emoticons autolink wordcount',
    toolbar: 'undo redo | blocks emoticons | bold italic underline | alignleft aligncenter alignright | indent outdent | bullist numlist | table',
    menubar: false,
    branding: false,
  });

</script>