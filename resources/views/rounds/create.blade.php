<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="" class="flex flex-col">
                    @csrf

                        <!-- Choose Course -->
                        <label for="course-select" class="floating-label mb-2">
                            Choose a course:
                        </label>
                        <select name="course_id" id="course-select" class="select select-bordered mb-6">
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>

                        <!-- Add players -->
                        <div id="players-container" class="flex flex-col items-start">
                            <h2 class="font-medium mb-2">=== Add players ===</h2>

                            <div class="player-row flex flex-col items-start" data-index="0">
                                <label class="floating-label mb-2">
                                    Player Name
                                </label>
                                <input type="text"
                                    name="players[0][display_name]"
                                    placeholder="Ronald McDonald"
                                    class="input input-bordered mb-6"
                                    required
                                    autofocus
                                >

                                <label class="floating-label mb-2">
                                    Player Email
                                </label>
                                <input type="email"
                                    name="players[0][email]"
                                    placeholder="ron@mcdonalds.com"
                                    class="input input-bordered mb-6"
                                    autofocus
                                >
                            </div>

                            <div class="form-control mt-8 flex items-center">
                                <button type="button" id="add-player" class="bg-green-800 text-white p-3 border-1 border-solid">
                                    Add player
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-control mt-8 flex items-center">
                            <button type="submit" class="bg-black text-white p-3 border-1 border-solid">
                                Lets Go!
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
