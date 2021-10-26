@extends('layouts.default')

@section('title', 'Create Credential')

@push('styles')
   <!-- Filepond stylesheet -->
  <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
@endpush

@section('content')

<!-- Page Content -->
<div class="container">
  <h1>Create Credential</h1>

  <form action="{{ route('credentials.store') }}" method="POST" enctype="multipart/form">
    @csrf
    <input name="description" />
    <!-- We'll transform this input into a pond -->
    <input type="file" name="media[]" class="filepond m-2">
    <button class="btn btn-primary" type="submit">Create Credential</button>
  </form>
</div>


@endsection

@push('body')
 <!-- Load FilePond library -->
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>

<script type="text/javascript">
        $(document).ready(function() {
         
            // Set default FilePond options
            FilePond.setOptions({
                acceptedFileTypes: ['image/jpeg', 'image/png'],
                allowFileEncode: true,
                allowImageExifOrientation: true,
                allowMultiple: true,
                maxFileSize: '25MB',
                server: {
                    process: function (fieldName, file, metadata, load, error, progress, abort, transfer, options) {
                        // fieldName is the name of the input field
                        // file is the actual file object to send
                        const formData = new FormData();
                        formData.append('filepond', file, file.name);
                        formData.append('path', "default");

                        const request = new XMLHttpRequest();
                        request.open('POST', '{{ route('filepond.process') }}');
                        request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                        // Should call the progress method to update the progress to 100% before calling load
                        // Setting computable to false switches the loading indicator to infinite mode
                        request.upload.onprogress = (e) => {
                            progress(e.lengthComputable, e.loaded, e.total);
                        };

                        // Should call the load method when done and pass the returned server file id
                        // this server file id is then used later on when reverting or restoring a file
                        // so your server knows which file to return without exposing that info to the client
                        request.onload = function() {
                            if (request.status >= 200 && request.status < 300) {
                                // the load method accepts either a string (id) or an object
                                load(request.responseText);
                            }
                            else {
                                // Can call the error method if something is wrong, should exit after
                                error('oh no');
                            }
                        };

                        request.send(formData);
                        
                        // Should expose an abort method so the request can be cancelled
                        return {
                            abort: () => {
                                // This function is entered if the user has tapped the cancel button
                                request.abort();

                                // Let FilePond know the request has been cancelled
                                abort();
                            }
                        };
                    },
                    revert: {
                        url: "{{ route('filepond.revert') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    restore: {
                        url: "{{ route('filepond.restore') }}?restore=",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                }
            });

            const pond = FilePond.create(document.querySelector('input[type="file"]'));

            // pond.on('init', function() {
            //     axios.get("{{ route('filepond.files', ['path' => 'default']) }}")
            //         .then(function(response) {
            //             response.data.forEach(function(file) {
            //                 pond.addFile(file, { type: 'limbo' });
            //             });
            //         });
            // });
        });
    </script>

@endpush