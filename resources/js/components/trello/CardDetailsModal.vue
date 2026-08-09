<script setup lang="ts">
import { router, useForm, usePage } from '@inertiajs/vue3'
import { Trash2 } from '@lucide/vue'
import { computed, watch } from 'vue'

import MemberAvatar from '@/components/trello/MemberAvatar.vue'
import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { update as cardUpdate, destroy as cardDestroy } from '@/routes/boards/cards'
import type { Team } from '@/types'


const props = defineProps<{
  card: {
    id: number
    title: string
    description: string | null
    members: Array<{ id: number; name: string }>
  }
  list: { name: string }
}>()

const open = defineModel<boolean>('open', { default: false })

const page = usePage()

const teamMembers = computed(
  () => (page.props.teamMembers as Array<{ id: number; name: string }> | undefined) ?? [],
)

const permissions = computed(
  () =>
    (page.props.permissions as { canUpdateCard: boolean; canDeleteCard: boolean } | undefined) ?? {
      canUpdateCard: false,
      canDeleteCard: false,
    },
)

const form = useForm({
  title: props.card.title,
  description: props.card.description ?? '',
  member_ids: props.card.members.map((member) => member.id),
})

watch(open, (isOpen) => {
  if (!isOpen) {
return
}

  form.title = props.card.title
  form.description = props.card.description ?? ''
  form.member_ids = props.card.members.map((member) => member.id)
  form.clearErrors()
})

function toggleMember(id: number, checked: boolean) {
  form.member_ids = checked
    ? [...form.member_ids, id]
    : form.member_ids.filter((memberId) => memberId !== id)
}

function routeArgs() {
  const currentTeam = page.props.currentTeam as Team | null
  const board = page.props.board as { slug: string } | null

  if (!currentTeam || !board) {
return null
}

  return { current_team: currentTeam.slug, board: board.slug, card: props.card.id }
}

function submit() {
  const args = routeArgs()

  if (!args) {
return
}

  form.patch(cardUpdate(args).url, {
    preserveScroll: true,
    onSuccess: () => {
      open.value = false
    },
  })
}

function destroyCard() {
  const args = routeArgs()

  if (!args) {
return
}

  router.delete(cardDestroy(args).url, {
    preserveScroll: true,
    onSuccess: () => {
      open.value = false
    },
  })
}
</script>

<template>
  <Dialog :open="open" @update:open="open = $event">
    <DialogContent class="sm:max-w-lg">
      <DialogHeader>
        <DialogTitle>Edit card</DialogTitle>
        <DialogDescription>In list "{{ list.name }}"</DialogDescription>
      </DialogHeader>

      <form class="space-y-4" @submit.prevent="submit">
        <div class="space-y-2">
          <Label for="card-title">Title</Label>
          <Input id="card-title" v-model="form.title" :disabled="!permissions.canUpdateCard" autofocus />
          <p v-if="form.errors.title" class="text-sm text-destructive">
            {{ form.errors.title }}
          </p>
        </div>

        <div class="space-y-2">
          <Label for="card-description">Description</Label>
          <textarea
            id="card-description"
            v-model="form.description"
            rows="4"
            :disabled="!permissions.canUpdateCard"
            placeholder="Add a more detailed description..."
            class="border-input placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 flex w-full rounded-md border bg-transparent px-3 py-2 text-base shadow-xs outline-none transition-[color,box-shadow] focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
          />
          <p v-if="form.errors.description" class="text-sm text-destructive">
            {{ form.errors.description }}
          </p>
        </div>

        <div class="space-y-2">
          <Label>Members</Label>
          <div
            v-if="teamMembers.length"
            class="max-h-40 space-y-1 overflow-y-auto rounded-md border border-border p-2"
          >
            <Label
              v-for="member in teamMembers"
              :key="member.id"
              class="flex items-center gap-2 rounded px-1 py-1.5 text-sm font-normal hover:bg-muted/50"
            >
              <Checkbox
                :model-value="form.member_ids.includes(member.id)"
                :disabled="!permissions.canUpdateCard"
                @update:model-value="(checked) => toggleMember(member.id, checked === true)"
              />
              <MemberAvatar :member="member" size="xs" />
              {{ member.name }}
            </Label>
          </div>
          <p v-else class="text-sm text-muted-foreground">No team members to assign.</p>
        </div>

        <DialogFooter class="sm:justify-between">
          <Button
            v-if="permissions.canDeleteCard"
            type="button"
            variant="ghost"
            class="text-destructive hover:text-destructive"
            @click="destroyCard"
          >
            <Trash2 class="h-4 w-4" />
            Delete
          </Button>

          <div class="flex gap-2">
            <Button type="button" variant="outline" @click="open = false">
              Cancel
            </Button>
            <Button v-if="permissions.canUpdateCard" type="submit" :disabled="form.processing">
              Save
            </Button>
          </div>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
