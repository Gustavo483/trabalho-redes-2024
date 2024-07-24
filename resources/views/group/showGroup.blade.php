<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>Messages : {{$idGrupo}}</div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" id="chat"></div>
            </div>
        </div>
    </div>

    <script type="module">
        const groupId = 1;

            Echo.channel('group.' + groupId)
            .listen('.message.sent', (e) => {
                console.log('Message received:', e);
                var chat = document.getElementById("chat");
                chat.innerHTML += `<p>${e.group.pk_group}</p>`;
            });
    </script>
</x-app-layout>
