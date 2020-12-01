<p style="text-align: center;"><h1>How to run Windows™ 95<sup>©</sup> on the web</h1></p>

## Setup:
* Download and install [dosbox](https://sourceforge.net/projects/dosbox/files/latest/download) to _C:\dosbox_
* Download [622C](http://www.rloe.com/randytheracer/622c.zip) and extract it into _C:\dosbox_
* Download and install [bochs](https://sourceforge.net/projects/bochs/files/latest/download) to _C:\bochs_
* Download and install [OSFMount](https://www.osforensics.com/downloads/osfmount.exe)
* Download and install [7-zip](https://www.7-zip.org/a/7z1900-x64.exe)
* Download the [windows 95 installation](https://winworldpc.com/download/4120c593-e280-9818-c39a-11c3a4e284a2/from/c39ac2af-c381-c2bf-1b25-11c3a4e284a2) and extract it to _C:\windows95_ using 7-zip
## Steps:
1. Copy _C:\bochs\bximage.exe_ into _C:\dosbox_ and run it, in the prompt press 1, and leave all the values as default (just press enter) except the hard disk size in megabytes which should be changed to 400
<details>
<summary>expected output</summary>

```bat
========================================================================
                                bximage
  Disk Image Creation / Conversion / Resize and Commit Tool for Bochs
         $Id: bximage.cc 13481 2018-03-30 21:04:04Z vruppert $
========================================================================

1. Create new floppy or hard disk image
2. Convert hard disk image to other format (mode)
3. Resize hard disk image
4. Commit 'undoable' redolog to base image
5. Disk image info

0. Quit

Please choose one [0] 1

Create image

Do you want to create a floppy disk image or a hard disk image?
Please type hd or fd. [hd]

What kind of image should I create?
Please type flat, sparse, growing, vpc or vmware4. [flat]

Choose the size of hard disk sectors.
Please type 512, 1024 or 4096. [512]

Enter the hard disk size in megabytes, between 10 and 8257535
[10] 400

What should be the name of the image?
[c.img]

Creating hard disk image 'c.img' with CHS=812/16/63 (sector size = 512)

The following line should appear in your bochsrc:
  ata0-master: type=disk, path="c.img", mode=flat
(The line is stored in your windows clipboard, use CTRL-V to paste)

Press any key to continue
```

</details>

2. Run _C:\dosbox\DOSBox.exe_ and in it run:
```bat
imgmount 2 c.img -size 512,63,16,812 -t hdd -fs none
boot 622c.img
fdisk
1
1
Y
```
3. When you see another prompt just press Enter to close DOSBox, then, open _C:\dosbox\DOSBox.exe_ again and run:
```bat
imgmount c c.img
boot 622c.img
format c:
y
win95
```
4. Close DOSBox, then, extract the content of all the image files in _C:\windows95_ (except _Boot.img_) into a new folder named _C:\win95_ (extract the image files themself by opening them in 7-zip)
5. Open OSFMount and choose _Mount new..._ you need to find the image disk (_C:\dosbox\c.img_) then choose _Mount all partitions_ option and uncheck the _read-only_ option, then press _ok_, now if you open the file explorer you will see a new drive (D: in my case), drag&drop the _C:\win95_ folder into the new drive, then in OSFMount click on the new drive and then on _Dismount_
6. Open _C:\dosbox\DOSBox.exe_ and run:
```bat
imgmount c c.img
boot 622c.img
mouse.com
c:
cd win95
setup /is
```
7. Close DOSBox, then from _C:\windows95\Boot.img_ extract _AUTOEXEC.BAT_ to a **new** folder named _win_ using 7-zip and place _C:\dosbox\c.img_ in the _win_ folder, finally, in the _win_ folder create a file named _dosbox.conf_ with this content:
<details>
<summary>Content</summary>

```conf
[sdl]
fullscreen=false
fulldouble=false
fullresolution=original
windowresolution=original
output=surface
autolock=true
sensitivity=100
waitonerror=true
priority=higher,normal
mapperfile=mapper-0.74-3.map
usescancodes=true
[dosbox]
language=
machine=svga_s3
captures=capture
memsize=16
[render]
frameskip=0
aspect=false
scaler=normal2x
[cpu]
core=normal
cputype=pentium_slow
cycles=auto
cycleup=10
cycledown=20
[mixer]
nosound=false
rate=44100
blocksize=1024
prebuffer=25
[midi]
mpu401=intelligent
mididevice=default
midiconfig=
[sblaster]
sbtype=sb16
sbbase=220
irq=7
dma=1
hdma=5
sbmixer=true
oplmode=auto
oplemu=default
oplrate=44100
[gus]
gus=false
gusrate=44100
gusbase=240
gusirq=5
gusdma=3
ultradir=C:\ULTRASND
[speaker]
pcspeaker=true
pcrate=44100
tandy=auto
tandyrate=44100
disney=true
[joystick]
joysticktype=auto
timed=true
autofire=false
swap34=false
buttonwrap=false
[serial]
serial1=dummy
serial2=dummy
serial3=disabled
serial4=disabled
[dos]
xms=true
ems=true
umb=true
keyboardlayout=auto
[ipx]
ipx=false
[autoexec]
imgmount c c.img 
boot c.img
```
</details>

8. Open bash command line (you might need to install it, you can follow [Chris Hoffman's guide](https://www.howtogeek.com/249966)) in the _win_ folder and run:
```bash
sudo apt-get update
sudo apt-get install python2.7
sudo apt-get install git
sudo apt-get install binutils
sudo apt-get install cmake
sudo apt-get install automake
cd ..
git clone https://github.com/warpcoil/em-dosbox
git clone https://github.com/emscripten-core/emsdk
mv win em-dosbox/src
cd emsdk
sudo autoreconf -f -i
./emsdk install latest
./emsdk activate latest
source ./emsdk_env.sh
cd ../em-dosbox
./autogen.sh
emconfigure ./configure --host=none-none-none (PPFLAGS="-s USE_SDL=2 -s USE_SDL_NET=2" LDFLAGS="-s USE_SDL_NET=2")
make
cd src
python packager.py win95 win AUTOEXEC.BAT
```
