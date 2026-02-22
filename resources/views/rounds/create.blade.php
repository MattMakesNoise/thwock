<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="" class="flex flex-col">
                    @csrf

                        <!-- Company -->
                        <label class="floating-label mb-2">
                            Company
                        </label>
                        <input type="text"
                            name="company"
                            placeholder="McDonalds"
                            class="input input-bordered mb-6"
                            required
                            autofocus>

                        <!-- Job Url -->
                        <label class="floating-label mb-2">
                            Jobs page url
                        </label>
                        <input type="url"
                            name="url"
                            placeholder="example.com/jobs"
                            class="input input-bordered mb-6"
                            required>

                        <!-- Keywords -->
                        <label class="floating-label mb-2">
                            Job keywords (comma separated)
                        </label>
                        <input type="text"
                            name="keywords"
                            placeholder="server, cook, security"
                            class="input input-bordered mb-6"
                            required>

                        <!-- Submit Button -->
                        <div class="form-control mt-8 flex items-center">
                            <button type="submit" class="bg-black text-white p-3 border-1 border-solid">
                                Add site
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
