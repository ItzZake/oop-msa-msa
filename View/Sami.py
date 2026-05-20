import random
import time
import tracemalloc
import cProfile

N = 1_000_000

def merge_sort(arr):
    if len(arr) <= 1:
        return arr
    mid = len(arr) // 2
    left = merge_sort(arr[:mid])
    right = merge_sort(arr[mid:])
    return merge(left, right)

def merge(left, right):
    result = []
    i = j = 0
    while i < len(left) and j < len(right):
        if left[i] <= right[j]:
            result.append(left[i]); i += 1
        else:
            result.append(right[j]); j += 1
    result.extend(left[i:])
    result.extend(right[j:])
    return result

random.seed(42)
arr = [random.randint(0, 10**9) for _ in range(N)]

# Memory tracking
tracemalloc.start()

# Execution time
start = time.perf_counter()
cProfile.run('merge_sort(arr)')
# sorted_arr = merge_sort(arr)
end = time.perf_counter()

current, peak = tracemalloc.get_traced_memory()
tracemalloc.stop()

print(f"Execution time:     {end - start:.4f}s")
print(f"Peak memory usage:  {peak / 1024 / 1024:.2f} MB")