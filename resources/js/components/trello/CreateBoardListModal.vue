<script setup lang="ts">
import { ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { Plus } from '@lucide/vue'

import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { store as listStore } from '@/routes/boards/lists'
import type { Team } from '@/types'

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
  const currentTeam = page.props.currentTeam as Team | null
  const board = page.props.board as { slug: string } | null

  if (!currentTeam || !board) return

  form.post(listStore({
    current_team: currentTeam.slug,
    board: board.slug,
  }).url, {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      open.value = false
    },
  })
}
</script>

<template>
  <Dialog v-model:open="open">
    <DialogTrigger as-child>
      <slot name="trigger">
        <Button variant="outline" size="sm">
          <Plus class="h-4 w-4" />
          Add list
        </Button>
      </slot>
    </DialogTrigger>

    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>Create list</DialogTitle>
        <DialogDescription>
          Give your list a name.
        </DialogDescription>
      </DialogHeader>

      <form class="space-y-4" @submit.prevent="submit">
        <div class="space-y-2">
          <Input v-model="form.name" placeholder="To do" autofocus />
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