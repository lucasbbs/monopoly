<select {{ $attributes->class(['select w-full appearance-none rounded-md border-2 border-gray-300 bg-gray-50 px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 disabled:cursor-not-allowed disabled:opacity-75 focus:border-red-500']) }}>
    {{ $slot }}
</select>