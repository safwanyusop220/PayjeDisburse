<template>
    <div class="menu-item" :class="{ opened: expanded }">
      <div
        class="label"
        @click="toggleMenu()"
        :style="{ paddingLeft: depth * 20 + 20 + 'px' }"
      >
        <div class="left">
          <i v-if="icon" class="material-icons-outlined">{{ icon }}</i>
          <span v-if="showLabel">{{ label }}</span>
        </div>
        <div v-if="data" class="right">
          <i class="expand material-icons" :class="{ opened: expanded }">expand_more</i>
        </div>
      </div>
      <div
        v-show="showChildren"
        :class="{ 'small-menu': smallMenu }"
        class="items-container"
        :style="{ height: containerHeight }"
        ref="container"
      >
        <menu-item
          :class="{ opened: showChildren }"
          v-for="(item, index) in data"
          :key="index"
          :data="item.children"
          :label="item.label"
          :icon="item.icon"
          :depth="depth + 1"
          :smallMenu="smallMenu"
        />
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, nextTick } from 'vue';
  
  const props = defineProps(['data', 'label', 'icon', 'depth', 'smallMenu']);
  
  const showChildren = ref(false);
  const expanded = ref(false);
  const containerHeight = ref(0);
  
  const showLabel = computed(() => {
    return props.smallMenu ? props.depth > 0 : true;
  });
  
  const toggleMenu = () => {
    expanded.value = !expanded.value;
    
    if (!showChildren.value) {
      showChildren.value = true;
      nextTick(() => {
        containerHeight.value = refs.container.scrollHeight + "px";
        setTimeout(() => {
          containerHeight.value = "fit-content";
          if (navigator.userAgent.indexOf("Firefox") !== -1) {
            containerHeight.value = "-moz-max-content";
          }
          refs.container.style.overflow = "visible";
        }, 300);
      });
    } else {
      containerHeight.value = refs.container.scrollHeight + "px";
      refs.container.style.overflow = "hidden";
      setTimeout(() => {
        containerHeight.value = 0 + "px";
      }, 10);
      setTimeout(() => {
        showChildren.value = false;
      }, 300);
    }
  };
  </script>
  
  
  <style lang="scss" scoped>
  .menu-item {
    position: relative;
    width: 100%;
    .label {
      width: 100%;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      white-space: nowrap;
      user-select: none;
      height: 50px;
      padding: 0 20px;
      box-sizing: border-box;
      font-size: 14px;
      color: #6a6a6a;
      transition: all 0.3s ease;
      > div {
        display: flex;
        align-items: center;
        gap: 10px;
      }
      i {
        font-size: 20px;
        color: #6e6e6e;
        transition: all 0.3s ease;
        &.expand {
          font-size: 16px;
          color: #cacaca;
          &.opened {
            transform: rotate(180deg);
          }
        }
      }
      &.small-item {
        width: fit-content;
      }
      &:hover {
        background: #deedff;
        cursor: pointer;
      }
    }
    .items-container {
      width: 100%;
      left: calc(100% + 6px);
      transition: height 0.3s ease;
      overflow: hidden;
      &.small-menu {
        width: fit-content;
        position: absolute;
        background: #fff;
        box-shadow: 0 0 10px #ebebeb;
        top: 0;
        .label {
          width: 100% !important;
          padding-left: 20px !important;
        }
      }
    }
  }
  </style>