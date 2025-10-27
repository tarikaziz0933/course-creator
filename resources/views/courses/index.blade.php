<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Course List</title>
  <link rel="stylesheet" href="{{ asset('css/course-list.css') }}">
</head>
<body>
  <div class="container">
    <h1>
      <span>All Courses</span>
      <a href="{{ route('courses.create') }}" class="btn">+ Add New Course</a>
    </h1>

    @if($courses->isEmpty())
      <div class="empty-state">
        <h3>No courses found 📭</h3>
        <p>Start by creating your first course.</p>
        <a href="{{ route('courses.create') }}" class="btn">Create Course</a>
      </div>
    @else
      @foreach($courses as $course)
        <div class="course-box">
          <h2>{{ $course->title }}</h2>
          <p><strong>Category:</strong> {{ $course->category ?? 'N/A' }}</p>
          <p>{{ $course->description }}</p>

          @if($course->feature_video)
            <video width="320" height="180" controls>
              <source src="{{ asset('storage/' . $course->feature_video) }}" type="video/mp4">
            </video>
          @endif

          @foreach($course->modules as $module)
            <div class="module-box">
              <h4>📘 {{ $module->title }}</h4>
              <p>{{ $module->description }}</p>

              @forelse($module->contents as $content)
                <div class="content-box">
                  <strong>{{ ucfirst($content->type) }}:</strong> {{ $content->title ?? 'Untitled' }} <br>
                  @if($content->type === 'text')
                    <p>{{ $content->body }}</p>
                  @elseif(in_array($content->type, ['image', 'video']))
                    @if($content->file_path)
                      @if($content->type === 'image')
                        <img src="{{ asset('storage/' . $content->file_path) }}" width="150">
                      @else
                        <video width="240" controls>
                          <source src="{{ asset('storage/' . $content->file_path) }}" type="video/mp4">
                        </video>
                      @endif
                    @endif
                  @elseif($content->type === 'link')
                    <a href="{{ $content->url }}" target="_blank">{{ $content->url }}</a>
                  @endif
                </div>
              @empty
                <p class="no-content">No content added yet for this module.</p>
              @endforelse
            </div>
          @endforeach
        </div>
      @endforeach
    @endif
  </div>
</body>
</html>

