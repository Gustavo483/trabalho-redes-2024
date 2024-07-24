<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>{{$idGrupo->st_name}} : </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" id="chat"></div>
            </div>
        </div>
    </div>

    <form id="messageForm"  method="post" action="{{route('sendMessege', ['group'=>$idGrupo->pk_group])}}">
        @csrf
        <div class="flex items-center">
            <label for="messege">Messege :</label>
            <input type="text" id="messege" name="st_message" required>
            <button class="ps-5 border" type="submit"> enviar</button>
        </div>
    </form>

    <script type="module">

        document.getElementById('messageForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            try {
                await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData,
                });
            } catch (error) {
                console.error('Error sending message:', error);
            }
        });

        const groupId = {{$idGrupo->pk_group}};

        Echo.channel('group.' + groupId)
            .listen('.message.sent', (e) => {
                console.log('Message received:', e);
                var chat = document.getElementById("chat");
                chat.innerHTML += `
                <div class="p-4 mb-2 bg-gray-100 rounded-lg">
                    <div class="flex items-center mb-2">
                        <div class="font-bold mr-2">${e.user.name}</div>
                    </div>
                    <div>${e.message.st_message}</div>
                </div>
            `;
            });
    </script>
</x-app-layout>
