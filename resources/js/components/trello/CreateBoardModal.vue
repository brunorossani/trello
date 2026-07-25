<script setup lang="ts">
import { ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { Plus } from '@lucide/vue';

import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { store as boardStore } from '@/routes/boards'

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog'

const page = usePage()
const open = ref(false)
const form = useForm({ name: '' })

function submit() {
    if (!page.props.currentTeam) return;

    form.post(boardStore.url({ current_team: page.props.currentTeam.slug }), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            open.value = false;
        },
    });
}
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <Button>
                <Plus />   
                New board
            </Button>
        </DialogTrigger>    

        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Create board</DialogTitle>
                <DialogDescription>
                    Give your board a name.
                </DialogDescription>
            </DialogHeader>

            <form class="space-y-4" @submit.prevent="submit">
                <div class="space-y-2">
                    <Input
                        v-model="form.name"
                        placeholder="Product roadmap"
                        autofocus
                    />
                    <p v-if="form.errors.name" class="text-sm text-destructive">
                        {{ form.errors.name }}
                    </p>
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="open = false">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        Create
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>