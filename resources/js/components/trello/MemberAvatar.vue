<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { computed } from 'vue'
import { Avatar, AvatarFallback } from '@/components/ui/avatar'
import { getInitials } from '@/composables/useInitials'
import { avatarColor } from '@/lib/colors'
import { cn } from '@/lib/utils'

const props = withDefaults(
  defineProps<{
    member: { id: number; name: string }
    size?: 'xs' | 'sm' | 'md'
    class?: HTMLAttributes['class']
  }>(),
  { size: 'sm' },
)

const sizeClasses: Record<string, string> = {
  xs: 'size-5 text-[9px]',
  sm: 'size-7 text-[11px]',
  md: 'size-9 text-sm',
}

const background = computed(() => avatarColor(props.member.id))
</script>

<template>
  <Avatar :class="cn(sizeClasses[size], 'ring-2 ring-background', props.class)" :title="member.name">
    <AvatarFallback class="font-semibold text-white" :style="{ backgroundColor: background }">
      {{ getInitials(member.name) }}
    </AvatarFallback>
  </Avatar>
</template>
