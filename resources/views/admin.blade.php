@extends('layouts.default')

@section('title', 'Admin')

@section('content')
<!-- Page Content -->
<div class="container" id="app">
 <h1 class="mt-4">Hello {{ auth()->user()->name }}</h1>
    <table class="table table-striped">
        <thead>
            <th>User</th>
            <th>Files</th>
            <th>Actions</th>
        </thead>
        <tbody>
            <tr v-for="user in users">
                <td>@{{ user.name }}</td>
                <td>
                    <a v-for="credential in user.credentials" 
                        v-bind:href="'/credential/'+ credential.id"
                        class="clearfix">
                        @{{ credential.description }}</a>
                </td>
                <td>
                    <button class="btn btn-danger" v-on:click="removeUser(user.id)">Remove</button>
                    <a class="btn btn-secondary" v-show="user.id !== {{ auth()->user()->id }}" 
                        v-bind:href="'/impersonate/'+ user.id">Impersonate</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@push('body')
<script type="text/javascript">
var app = new Vue({
  el: '#app',
  data: {
      users: @json($users)
  },
  methods: {
    load() {
    },
    removeUser(userId) {
        let self = this;
        if(confirm('Are you sure?')) {
              fetch('/users/remove/'+userId)
                .then(response => response.json())
                .then(data => {
                    self.fetchUsers();
                });
        }
    },
    fetchUsers() {
        let self = this;
        
        fetch('/users/list')
            .then(response => response.json())
            .then(data => {
                self.users = data;
            });
    }
  },
  mounted() {
    let self = this;
    self.load();
  }
})
</script>
@endpush