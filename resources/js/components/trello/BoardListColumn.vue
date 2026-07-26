<script setup lang="ts">
import { Pencil, Plus } from '@lucide/vue'
import { Button } from '@/components/ui/button'

defineProps<{
  list: {
    id: number
    name: string
    cards: Array<{
      id: number
      title: string
      description: string | null
      dueAt: string | null
      members: Array<{ id: number; name: string }>
    }>
  }
}>()
</script>
<template>
<section class="flex w-[300px] shrink-0 flex-col gap-2 rounded-2xl border border-sidebar-border/70 bg-muted/30 p-3">    
    <!-- Header -->
    <div class="flex items-center justify-between gap-2 px-1 shrink-0">
      <h2 class="text-sm font-medium text-foreground">{{ list.name }}</h2>
      <div class="flex items-center gap-1">
        <span class="rounded-full bg-background px-2 py-0.5 text-[11px] font-medium text-muted-foreground">
          {{ list.cards.length }}
        </span>
        <Button variant="ghost" size="icon" class="h-6 w-6 text-muted-foreground hover:text-foreground">
          <Pencil class="h-3.5 w-3.5" />
        </Button>
      </div>
    </div>

    <!-- Cards — scroll interno -->
    <div class="flex flex-col gap-2 overflow-y-auto">
      <article
        v-for="card in list.cards"
        :key="card.id"
        class="cursor-pointer rounded-xl border border-border bg-card p-3 shadow-sm transition-shadow hover:shadow-md"
      >
        <div class="flex items-start justify-between gap-2">
          <p class="text-sm font-medium leading-snug">{{ card.title }}</p>
          <span v-if="card.dueAt" class="shrink-0 rounded-full bg-muted px-2 py-0.5 text-[10px] text-muted-foreground">
            {{ card.dueAt }}
          </span>
        </div>

        <p v-if="card.description" class="mt-1.5 text-xs text-muted-foreground line-clamp-2">
          {{ card.description }}
        </p>

        <div v-if="card.members.length" class="mt-2.5 flex flex-wrap gap-1.5">
          <span
            v-for="member in card.members"
            :key="member.id"
            class="rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-medium text-primary"
          >
            {{ member.name }}
          </span>
        </div>
      </article>
    </div>

    <!-- Add card — fijo abajo -->
    <button
      type="button"
      class="flex shrink-0 items-center gap-1.5 rounded-xl px-2 py-1.5 text-xs text-muted-foreground transition-colors hover:bg-muted/60 hover:text-foreground"
    >
      <Plus class="h-3.5 w-3.5" />
      Add card
    </button>

  </section>
</template>