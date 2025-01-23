function toggleSubMenu(id: any) {
  const menu = document.getElementById(id);
  menu?.classList.toggle('hidden');
  menu?.classList.toggle('flex');
}

//@ts-ignore
window.toggleSubMenu = toggleSubMenu;