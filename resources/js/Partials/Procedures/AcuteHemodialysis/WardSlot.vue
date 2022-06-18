<template>
    <div>
        <div
            class="w-full p-2 md:p-4 rounded shadow bg-green-300"
            v-for="(slot, key) in 6"
            :key="key"
        >
            I'm slot here
        </div>
    </div>
</template>