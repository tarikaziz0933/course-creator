<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Course List</title>
  <style>
    body{font-family:Inter, Arial, sans-serif;background:#f3f6fb;padding:20px}
    .container{max-width:1100px;margin:20px auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.05)}
    h1{display:flex;justify-content:space-between;align-items:center}
    .course-box{border:1px solid #e2e8f0;padding:15px;margin-bottom:15px;border-radius:8px;background:#f8fafc}
    .module-box{margin-left:20px;margin-top:10px;border-left:2px solid #cbd5e1;padding-left:10px}
    .content-box{margin-left:30px;margin-top:8px;border-left:2px dashed #cbd5e1;padding-left:10px}
    a.btn{background:#2b8cff;color:#fff;padding:6px 10px;border-radius:6px;text-decoration:none}
  </style>
</head>
<body>
  <div class="container">
    <h1>
      <span>All Courses</span>
      <a href="{{ route('courses.create') }}" class="btn">+ Add New Course</a>
    </h1>

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

            @foreach($module->contents as $content)
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
            @endforeach
          </div>
        @endforeach
      </div>
    @endforeach
  </div>
</body>
</html>
