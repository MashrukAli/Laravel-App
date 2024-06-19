<x-layout :doctitle="$post->title">
  <div class="container py-md-5 container--narrow">
      <div class="d-flex justify-content-between">
          <h2>{{$post->title}}</h2>
          @can('update', $post)
          <span class="pt-2">
              <a href="/post/{{$post->id}}/edit" class="text-primary mr-2" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
              <form class="delete-post-form d-inline" action="/post/{{$post->id}}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>
              </form>
          </span>
          @endcan
      </div>

      <p class="text-muted small mb-4">
          <a href="#"><img class="avatar-tiny" src="https://gravatar.com/avatar/f64fc44c03a8a7eb1d52502950879659?s=128" /></a>
          Posted by <a href="#">{{$post->user->username}}</a> on {{$post->created_at->format('n/j/Y')}}
      </p>

      <div class="body-content">
          {!! $post->body !!}
      </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script>
      $(function () {
          $('[data-toggle="tooltip"]').tooltip();
      });
  </script>
</x-layout>
