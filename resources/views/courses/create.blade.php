<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create Course</title>
  <link rel="stylesheet" href="{{ asset('css/course.css') }}">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  <div class="container">
    <div class="header-row">
      <h1>Create a Course</h1>
      <a href="{{ route('courses.index') }}" class="btn btn-secondary">← Back to Course List</a>
    </div>

    @if ($errors->any())
      <div class="alert-error">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(session('success'))
      <div class="alert-success">
        {{ session('success') }}
      </div>
    @endif

    <form id="course-form" action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="field">
        <label>Course Title *</label>
        <input type="text" name="title" required placeholder="Enter course title">
      </div>

      <div class="flex-gap">
        <div class="flex-1">
          <label>Category</label>
          <input type="text" name="category" placeholder="Enter category">
        </div>
      </div>

      <div class="field">
        <label>Course Description</label>
        <textarea name="description" rows="4" placeholder="Enter course description"></textarea>
      </div>

      <hr>

      <div class="module-title-row">
        <h3>Modules</h3>
        <button type="button" id="add-module-btn" class="btn">+ Add Module</button>
      </div>

      <div id="modules-wrapper">
        <!-- Modules will be appended here dynamically -->
      </div>

      <div class="actions">
        <button type="submit" class="btn">Save Course</button>
        <a href="#" onclick="window.location.reload(); return false;" class="btn btn-danger" style="margin-left:8px">Cancel</a>
      </div>
    </form>
  </div>

  <!-- Module template -->
  <template id="module-template">
    <div class="module-box" data-module-index="__MODULE_INDEX__">
      <div class="module-header">
        <strong>Module __MODULE_NUMBER__</strong>
        <div>
          <button type="button" class="btn toggle-module">Toggle</button>
          <button type="button" class="btn btn-danger remove-module">Remove</button>
        </div>
      </div>

      <div class="module-body">
        <div class="field">
          <label>Module Title *</label>
          <input type="text" name="modules[__MODULE_INDEX__][title]" required>
        </div>
        <div class="field">
          <label>Module Description</label>
          <textarea name="modules[__MODULE_INDEX__][description]" rows="2"></textarea>
        </div>

        <div class="module-title-row">
          <h4 style="margin:0">Contents</h4>
          <button type="button" class="btn add-content">+ Add Content</button>
        </div>

        <div class="module-contents">
          <!-- content items appended here -->
        </div>
      </div>
    </div>
  </template>

  <!-- Content template -->
  <template id="content-template">
    <div class="content-box" data-content-index="__CONTENT_INDEX__">
      <div class="module-title-row">
        <strong>Content __CONTENT_NUMBER__</strong>
        <button type="button" class="btn btn-danger remove-content">Remove</button>
      </div>

      <div style="margin-top:8px">
        <div class="field">
          <label>Content Title</label>
          <input type="text" name="modules[__MODULE_INDEX__][contents][__CONTENT_INDEX__][title]">
        </div>

        <div class="field">
          <label>Content Type</label>
          <select name="modules[__MODULE_INDEX__][contents][__CONTENT_INDEX__][type]" class="content-type">
            <option value="text">Text</option>
            {{-- <option value="image">Image</option> --}}
            {{-- <option value="video">Video (file)</option> --}}
            <option value="link">External Link</option>
          </select>
        </div>

        <div class="field field-text">
          <label>Body (text)</label>
          <textarea name="modules[__MODULE_INDEX__][contents][__CONTENT_INDEX__][body]" rows="2"></textarea>
        </div>


        <div class="field field-url" style="display:none">
          <label>URL</label>
          <input type="text" name="modules[__MODULE_INDEX__][contents][__CONTENT_INDEX__][url]">
        </div>
      </div>
    </div>
  </template>

  <script src="{{ asset('js/course-form.js') }}"></script>
</body>
</html>
