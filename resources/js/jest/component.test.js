const sum = require('./component');

import Vue from "vue";
import Game from "../components/Game.vue";

test('adds 1 + 2 to equal 3', () => {
    expect(sum(1, 2)).toBe(3);
  });