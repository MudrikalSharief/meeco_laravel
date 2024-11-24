<x-layout>

    <h1>hello</h1>
    @auth
        <h1>I am auth i logged good</h1>
    @endauth

    @guest
        <h1>I am guest i dont logged in</h1>
    @endguest
</x-layout>