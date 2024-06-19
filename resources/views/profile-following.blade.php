<x-profile :sharedData="$sharedData" doctitle="Who {{$sharedData ['username']}}">
    @include('profile-following-only')
  </x-profile>