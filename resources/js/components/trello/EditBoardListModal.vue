<script setup lang="ts">
import { router, useForm, usePage } from '@inertiajs/vue3'
import { Trash2 } from '@lucide/vue'
import { computed, watch } from 'vue'

import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { update as listUpdate, destroy as listDestroy } from '@/routes/boards/lists'
import type { Team } from '@/types'

const props = defineProps<{
  list: { id: number; name: string }
}>()

const open = defineModel<boolean>('open', { default: false })

const page = usePage()

const permissions = computed(
  () =>
    (page.props.permissions as
      | { canUpdateBoardList: boolean; canDeleteBoardList: boolean }
      | undefined) ?? { canUpdateBoardList: false, canDeleteBoardList: false },
)

const form = useForm({ name: props.list.name })

watch(open, (isOpen) => {
  if (!isOpen) {
    return
  }

  form.name = props.list.name
  form.clearErrors()
})

function routeArgs() {
  const currentTeam = page.props.currentTeam as Team | null
  const board = page.props.board as { slug: string } | null

  if (!currentTeam || !board) {
    return null
  }

  return { current_team: currentTeam.slug, board: board.slug, list: props.list.id }
}

function submit() {
  const args = routeArgs()

  if (!args) {
    return
  }

  form.patch(listUpdate(args).url, {
    preserveScroll: true,
    onSuccess: () => {
      open.value = false
    },
  })
}

function destroyList() {
  const args = routeArgs()

  if (!args) {
    return
  }

  router.delete(listDestroy(args).url, {
    preserveScroll: true,
    onSuccess: () => {
      open.value = false
    },
  })
}
</script>

<template>
  <Dialog :open="open" @update:open="open = $event">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>Edit list</DialogTitle>
        <DialogDescription>Rename or delete this list.</DialogDescription>
      </DialogHeader>

      <form class="space-y-4" @submit.prevent="submit">
        <div class="space-y-2">
          <Input v-model="form.name" :disabled="!permissions.canUpdateBoardList" autofocus />
          <p v-if="form.errors.name" class="text-sm text-destructive">
            {{ form.errors.name }}
          </p>
        </div>

        <DialogFooter class="sm:justify-between">
          <Button
            v-if="permissions.canDeleteBoardList"
            type="button"
            variant="ghost"
            class="text-destructive hover:text-destructive"
            @click="destroyList"
          >
            <Trash2 class="h-4 w-4" />
            Delete
          </Button>

          <div class="flex gap-2">
            <Button type="button" variant="outline" @click="open = false">
              Cancel
            </Button>
            <Button v-if="permissions.canUpdateBoardList" type="submit" :disabled="form.processing">
              Save
            </Button>
          </div>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
