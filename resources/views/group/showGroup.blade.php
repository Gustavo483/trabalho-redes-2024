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

    <form id="messageForm"  method="post" action="{{route('sendMessege', ['group'=>$idGrupo->pk_group])}}" enctype="multipart/form-data">
        @csrf
        <div class="flex justify-center">
            <div>
                <label for="messege">Messege :</label>
                <input class="block mt-5" type="text" id="messege" name="st_message">
                <div>
                    <input class="mt-5" id="inputFile" type="file" name="file" accept="image/*,audio/*">
                </div>
                <button class="mt-5 border" type="submit"> enviar</button>
            </div>
        </div>
    </form>
    @if (session('success'))
        <div>{{ session('success') }}</div>
        @if (session('fileUrl'))
            <div><a href="{{ session('fileUrl') }}" target="_blank">Download File</a></div>
        @endif
    @endif

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
                var chat = document.getElementById("chat");

                let messageContent = '';

                if (e.message.st_message) {
                    messageContent += `<div>${e.message.st_message}</div>`;
                }

                if (e.message.url_file_audio) {

                    messageContent += `<div><a href="${e.message.url_file_audio}" target="_blank">Download File</a></div>`;

                    if (e.file_type.startsWith('audio/')) {
                        messageContent += `
                    <div>
                        <audio controls>
                            <source src="${e.message.url_file_audio}" type="${e.file_type}">
                            Your browser does not support the audio element.
                        </audio>
                    </div>`;
                    } else if (e.file_type.startsWith('image/')) {
                        messageContent += `
                    <div>
                        <img src="${e.message.url_file_audio}" alt="${e.message.name_file}" style="width: 400px; height: 300px;">
                    </div>`;
                    } else {
                        messageContent += `<div><a href="${e.message.url_file_audio}" target="_blank">Abrir arquivo: "${e.name_file}"</a></div>`;
                    }
                }

                chat.innerHTML += `
            <div class="p-4 mb-2 bg-gray-100 rounded-lg">
                <div class="flex items-center mb-2">
                    <div class="font-bold mr-2">${e.user.name}</div>
                </div>
                ${messageContent}
            </div>`;
            });
    </script>
</x-app-layout>
