@extends('cms-core::install.layout')

@section('content')
<div class="mb-6">
  <h2 class="text-xl font-bold text-gray-900 dark:text-white">Step 2: Database Configuration</h2>
  <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Configure your database host, name, and credentials.</p>
</div>

<form action="{{ route('install.save_db') }}" method="POST" id="db-form" class="space-y-5">
  @csrf

  <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
    <!-- Database Driver -->
    <div class="md:col-span-2 mb-[20px]">
      <label for="driver">
        <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Database Driver</p>
      </label>
      <div class="form-control">
        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
          <select name="driver" id="driver" class="input flex-1 bg-transparent text-gray-600 focus:outline-none dark:text-gray-dark-300 w-full py-3 px-4">
            <option value="mysql" {{ $dbConfig['driver'] == 'mysql' ? 'selected' : '' }}>MySQL / MariaDB</option>
            <option value="pgsql" {{ $dbConfig['driver'] == 'pgsql' ? 'selected' : '' }}>PostgreSQL</option>
            <option value="sqlite" {{ $dbConfig['driver'] == 'sqlite' ? 'selected' : '' }}>SQLite</option>
            <option value="sqlsrv" {{ $dbConfig['driver'] == 'sqlsrv' ? 'selected' : '' }}>SQL Server</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Database Host -->
    <div class="mb-[20px]">
      <label for="host">
        <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Database Host</p>
      </label>
      <div class="form-control">
        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
          <input type="text" name="host" id="host" value="{{ old('host', $dbConfig['host']) }}" class="input flex-1 bg-transparent text-gray-600 focus:outline-none dark:text-gray-dark-300 w-full py-3 px-4">
        </div>
      </div>
    </div>

    <!-- Port -->
    <div class="mb-[20px]">
      <label for="port">
        <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Port</p>
      </label>
      <div class="form-control">
        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
          <input type="text" name="port" id="port" value="{{ old('port', $dbConfig['port']) }}" class="input flex-1 bg-transparent text-gray-600 focus:outline-none dark:text-gray-dark-300 w-full py-3 px-4">
        </div>
      </div>
    </div>

    <!-- Database Name -->
    <div class="mb-[20px] md:col-span-2">
      <label for="database">
        <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Database Name</p>
      </label>
      <div class="form-control">
        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
          <input type="text" name="database" id="database" value="{{ old('database', $dbConfig['database']) }}" required class="input flex-1 bg-transparent text-gray-600 focus:outline-none dark:text-gray-dark-300 w-full py-3 px-4">
        </div>
      </div>
    </div>

    <!-- Username -->
    <div class="mb-[20px]">
      <label for="username">
        <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Username</p>
      </label>
      <div class="form-control">
        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
          <input type="text" name="username" id="username" value="{{ old('username', $dbConfig['username']) }}" class="input flex-1 bg-transparent text-gray-600 focus:outline-none dark:text-gray-dark-300 w-full py-3 px-4">
        </div>
      </div>
    </div>

    <!-- Password -->
    <div class="mb-[20px]">
      <label for="password">
        <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Password</p>
      </label>
      <div class="form-control">
        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
          <input type="password" name="password" id="password" value="{{ old('password', $dbConfig['password']) }}" class="input flex-1 bg-transparent text-gray-600 focus:outline-none dark:text-gray-dark-300 w-full py-3 px-4">
        </div>
      </div>
    </div>

  </div>

  <!-- Test Feedback Alert -->
  <div id="test-alert" class="hidden p-4 rounded-xl text-xs sm:text-sm font-bold"></div>

  <!-- Actions -->
  <div class="flex items-center justify-between pt-4 border-t border-[#E8EDF2] dark:border-[#1B254B]">
    <div class="flex items-center gap-3">
      <a href="{{ route('install.step1') }}" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-gray-200 hover:bg-gray-300 border-neutral-bg dark:border-dark-neutral-bg py-[11px] px-[23px] text-gray-700 font-bold rounded-lg">
        ← Back
      </a>
      <button type="button" id="btn-test" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-yellow-500 hover:bg-yellow-600 border-neutral-bg dark:border-dark-neutral-bg py-[11px] px-[23px] text-white font-bold rounded-lg">
        Test Connection
      </button>
    </div>

    <button type="submit" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg py-[11px] px-[23px] text-white">
      Save & Continue →
    </button>
  </div>
</form>
@endsection

@section('scripts')
<script>
  document.getElementById('btn-test').addEventListener('click', function() {
    const alertBox = document.getElementById('test-alert');
    alertBox.className = 'p-4 rounded-xl text-xs sm:text-sm font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800';
    alertBox.innerText = 'Connecting to database server...';
    alertBox.classList.remove('hidden');

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('driver', document.getElementById('driver').value);
    formData.append('host', document.getElementById('host').value);
    formData.append('port', document.getElementById('port').value);
    formData.append('database', document.getElementById('database').value);
    formData.append('username', document.getElementById('username').value);
    formData.append('password', document.getElementById('password').value);

    fetch('{{ route("install.test_db") }}', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alertBox.className = 'p-4 rounded-xl text-xs sm:text-sm font-bold bg-green-50 text-green-700 dark:bg-green-950/50 dark:text-green-300 border border-green-200 dark:border-green-800';
        alertBox.innerText = '✓ ' + data.message;
      } else {
        alertBox.className = 'p-4 rounded-xl text-xs sm:text-sm font-bold bg-red-50 text-red-700 dark:bg-red-950/50 dark:text-red-300 border border-red-200 dark:border-red-800';
        alertBox.innerText = '✗ ' + data.message;
      }
    })
    .catch(err => {
      alertBox.className = 'p-4 rounded-xl text-xs sm:text-sm font-bold bg-red-50 text-red-700 dark:bg-red-950/50 dark:text-red-300 border border-red-200 dark:border-red-800';
      alertBox.innerText = '✗ Network error testing database.';
    });
  });
</script>
@endsection
