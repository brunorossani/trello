/**
 * Deterministic hue for a given numeric id, spread using the golden angle
 * so consecutive ids land on visually distinct colors.
 */
function hueFromId(id: number): number {
  return (id * 137.508) % 360
}

export function avatarColor(id: number): string {
  return `hsl(${hueFromId(id)} 55% 45%)`
}

export function boardAccent(id: number): { from: string; to: string } {
  const hue = hueFromId(id)

  return {
    from: `hsl(${hue} 70% 52%)`,
    to: `hsl(${(hue + 45) % 360} 70% 46%)`,
  }
}
