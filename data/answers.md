# LBAW Answers

## 1. Using async/await with a forEach loop

Sure the code does work, but I'm pretty sure it doesn't do what you expect it to do. It just fires off multiple asynchronous calls, but the `printFiles` function does immediately return after that.

### Reading in sequence

If you want to read the files in sequence, you cannot use forEach indeed. Just use a modern for … of loop instead, in which await will work as expected:

```js
async function printFiles() {
  const files = await getFilePaths();

  for (const file of files) {
    const contents = await fs.readFile(file, "utf8");
    console.log(contents);
  }
}
```

### Reading in parallel

If you want to read the files in parallel, you cannot use `forEach` indeed. Each of the `async` callback function calls does return a promise, but you're throwing them away instead of awaiting them. Just use map instead, and you can await the array of promises that you'll get with `Promise.all`:

```js
async function printFiles() {
  const files = await getFilePaths();

  await Promise.all(
    files.map(async (file) => {
      const contents = await fs.readFile(file, "utf8");
      console.log(contents);
    })
  );
}
```

> Could you please explain why does `for ... of ...` work? – _AFONSO Aug 15 '16 at 18:04_

> Ok i know why... Using **Babel** will transform `async`/`await` to generator function and using `forEach` means that each iteration has an individual generator function, which has nothing to do with the others. so they will be executed independently and has no context of `next()` with others. Actually, a simple `for()` loop also works because the iterations are also in one single generator function. – _AFONSO Aug 15 '16 at 19:21_

> **@AFONSO**: In short, because it was designed to work :-) await suspends the current function evaluation, including all control structures. Yes, it is quite similar to generators in that regard (which is why they are used to polyfill async/await). – _BRUNO Aug 15 '16 at 23:28_

> So `files.map(async (file) => ...` is equivalent to `files.map((file) => new Promise((rej, res) => { ...`? - _CARLOS Mar 29 '17 at 11:13_

> **@CARLOS** Not really, an async function is quite different from a Promise executor callback, but yes the map callback returns a promise in both cases. – _BRUNO Mar 29 '17 at 16:25_

> When you come to learn about JS promises, but instead use half an hour translating latin. Hope you're proud @Bergi ;) – _DANIEL May 16 '17 at 21:04_

---

The p-iteration module on npm implements the Array iteration methods so they can be used in a very straightforward way with async/await.

An example with your case:

```js
const { forEach } = require("p-iteration");
const fs = require("fs-promise");

async function printFiles() {
  const files = await getFilePaths();

  await forEach(files, async (file) => {
    const contents = await fs.readFile(file, "utf8");
    console.log(contents);
  });
}
```

<br><br><br>

## 2. How can you find out which process is listening on a TCP or UDP port on Windows?

### On Powershell

TCP

```
Get-Process -Id (Get-NetTCPConnection -LocalPort YourPortNumberHere).OwningProcess
```

UDP

```
Get-Process -Id (Get-NetUDPEndpoint -LocalPort YourPortNumberHere).OwningProcess
```

### On CMD

```
C:\> netstat -a -b
```

<br><br><br>

## 3. Whats the difference between backing up my photos on iPhone and storing them in iCloud?

Cloud Photos is for syncing between your devices and replacing your photos if you lose your device. It retains a full copy of your photos in iCloud (accessible on iCloud.com as well).

iCloud Backup is a one-way backup of your current photo library on your device. It can only be restored by restoring your entire iCloud Backup.

You can safely turn off Photos from your backup - unless you prefer to manually manage your photos, I'd recommend doing this instead of turning off iCloud Photos.

If iCloud Photo Library is on, there will be a note saying "Photo Library is backed up separately as part of iCloud Photos" - if you don't see this message, double check that your device is actually syncing with iCloud Photos. You can do this in the Photos Setting or in iCloud Settings > Photos.

<br><br><br>

## 4. How can I install pip on Windows?

```
python get-pip.py
```

<br><br><br>

## 5. How to remove the Flutter debug banner?

On your `MaterialApp` set `debugShowCheckedModeBanner` to `false`.

```dart
MaterialApp(
  debugShowCheckedModeBanner: false,
)
```

The debug banner will also automatically be removed on release build.

<br><br><br>

## 6. PS4 free monthly games

Once you added the PS+ game to your library, you can download and play it any time, as long as you still have PS+. If you stop having PS+, you can no longer play/download the game AFAIK.

<br><br><br>

## 7. Beginning AI programming

Classical application areas of AI:

- Robotics
- Search
- Natural Language Processing
- Knowledge Representation / Expert Systems
- Planning / Scheduling

Various algorithmic approaches:

- Neural Networks
- Evolutionary / Genetic Algorithms
- Automatic Reasoning
- Logic Programming
- Probablilistic Approaches

Recommendable books:

- Norvig, Russel: Artificial Intelligence - A Modern Approach
- Norvig: Paradigms of Artificial Intelligence Programming (uses Lisp)
- Bratko: Prolog Programming for Artificial Intelligence

Recommendable programming languages:

- Prolog
- Lisp
- Java (many algorithms are discussed in Java nowadays)

There are also a number of interesting answers to this question (which sort of covers the same ground).

<br><br><br>

## 8. What does the z-buffer look like in memory?

The Z buffer used to be specialized memory set aside for a single purpose, some web sites still explain it like that, but no longer.

Now the Z buffer is just a chunk of memory you allocate, or an API like OpenGL allocates on your behalf.

The size of that chunk of memory will depend on the type of the Z buffer values, in the example you gave it is a 32bit [floating point] values, but 24 bits is also very common. Always choose the smallest size the program needs as it can have a large effect on the performance of the application. It is indeed multiplied by the size of framebuffer so 8mb is correct for the example you gave.

The values that get stored in it are the depth values for any geometry drawn to the associated framebuffer. It is important to realize that these values are NOT the linear values of the MVP matrix computed in the vertex shader so the Z buffer can not be used for things like shadow maps.

Fragments resulting from each draw call have their depth values tested against existing values in the Z buffer and if the test passes the GPU will write that fragment and update the Z buffer with the new value, if not the fragment gets discarded and the Z buffer is left untouched.

A few other little details:

The Z buffer is generally cleared at the beginning of each frame with a clear value that can be set (or must be set) via the API. This becomes the default value that writes to the Z buffer are tested against.

GPU's have specialized hardware for writing the Z buffer, this hardware can speed up writing to memory by a factor of 2 or more and can be leveraged when creating things like shadow maps, so it is not limited for use with just the Z buffer.

Depth testing can be turned off/on for each draw call, which can be useful.

<br><br><br>

## 9. Disable info popups on YouTube app

<br><br><br>

## 10. Can comments be used in JSON?

No.

The JSON is data only, and if you include a comment, then it will be data too.

You could have a designated data element called "\_comment" (or something) that should be ignored by apps that use the JSON data.

You would probably be better having the comment in the processes that generates/receives the JSON, as they are supposed to know what the JSON data will be in advance, or at least the structure of it.
