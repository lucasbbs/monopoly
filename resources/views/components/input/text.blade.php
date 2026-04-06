@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'flex h-10 w-full rounded-md bg-background px-3 py-2 text-base ring-red-500 file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm font-body border-2 border-border focus:border-red-500']) }}>
