# LBAW Questions

## 1. Using async/await with a forEach loop

Are there any issues with using `async`/`await` in a `forEach` loop? I'm trying to loop through an array of files and `await` on the contents of each file.

```js
import fs from "fs-promise";

async function printFiles() {
  const files = await getFilePaths(); // Assume this works fine

  files.forEach(async (file) => {
    const contents = await fs.readFile(file, "utf8");
    console.log(contents);
  });
}

printFiles();
```

This code does work, but could something go wrong with this? I had someone tell me that you're not supposed to use `async`/`await` in a higher-order function like this, so I just wanted to ask if there was any issue with this.

> JavaScript\
> Node.JS

<br><br><br>

## 2. How can you find out which process is listening on a TCP or UDP port on Windows?

How can you find out which process is listening on a TCP or UDP port on Windows?

> Windows\
> Networks

<br><br><br>

## 3. Whats the difference between backing up my photos on iPhone and storing them in iCloud?

If I go to settings -> iCloud -> manage storage There are 2 categories:

1. Backups (118 GB)
2. Photos (18.3 GB)

In my backups, there's photo library (109.25 GB)

I'm confused, what's the difference between storing my photos in iCloud and backing them up then storing them in iCloud.

What happens if I click on " turn off and delete" the backup for my photo library?

> iPhone\
> Cloud\
> Photos

<br><br><br>

## 4. How can I install pip on Windows?

`pip` is a replacement for `easy_install`. But should I install `pip` using `easy_install` on Windows? Is there a better way?

> Python\
> Windows

<br><br><br>

## 5. How to remove the Flutter debug banner?

How to remove the debug banner in flutter?

I am using flutter screenshot and I would like the screenshot not to have banner. Now it does have.

Note that I get not supported for emulator message for profile and release mode.

> Flutter\
> Smartphones

<br><br><br>

## 6. PS4 free monthly games

I have a question. I am subscribed to PS+, and I started downloading a free game in June. Can I download it anytime I want or is there a deadline before they charge me for it?

> Playstation

<br><br><br>

## 7. Beginning AI programming

I am really interested in AI and want to start programming in this field. What are the various areas within AI? e.g. Neural Networks etc.

What book can be recommended for a beginner in AI and are there any preferred languages used in the field of AI?

> AI\
> Machine Learning

<br><br><br>

## 8. What does the z-buffer look like in memory?

I am an amateur game developer, and I've been reading a lot on how computer graphics rendering works lately. I was reading on the Z-buffer recently and I can't quite seem to be able to wrap my head around what exactly the Z-buffer looks like in terms of memory. It's described to contain depth information for each fragment that will be drawn on-screen, and that modern Z-buffers have 32-bits, so would that mean that on a, let's say, 1920x1080 screen it'd be just above 8MB (1920 _ 1080 _ 32) per frame?

I still don't quite understand what this value would be to a GPU (maybe it can crunch it easily), or if this value is even correct. Most demostrative implementations I found implement the Z-buffer as a simple array with size (height \* width), so I'm basing myself off of that.

> Graphics Card\
> Computers

<br><br><br>

## 9. Disable info popups on YouTube app

When viewing videos with the YouTube app, constant info message popups during playback shows. Videos that show those info popups show a white circle with the letter 'i'.

Is there a way to disable that?

> Smartphones\
> YouTube

<br><br><br>

## 10. Can comments be used in JSON?

Can I use comments inside a JSON file? If so, how?

> JSON
